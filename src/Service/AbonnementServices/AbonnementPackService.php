<?php

namespace App\Service\AbonnementServices ;

use DateTimeImmutable;
use App\Entity\Abonnement;
use App\Entity\ContenuAbonnement;
use App\Repository\EtudiantRepository;
use App\Repository\FormationRepository;
use App\Repository\AbonnementRepository;
use App\Repository\FormateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DateServices\DateService;
use App\Repository\PackFormationRepository;
use App\Repository\ContenuAbonnementRepository;
use App\Repository\CentresDeFormationRepository;
use App\Repository\ContenuPackFormationRepository;
use App\Service\IndemnitesDeFormateuresServices\CreateIndemnitesService;
use App\Service\IndemnitesDeFormateuresServices\ObtenirIndemnitesService;
use App\Service\IndemnitesDeFormateuresServices\SupprimerIdemnitesService;

class AbonnementPackService{
    public function __construct (
        AbonnementRepository $abonnementRepo ,
        EntityManagerInterface $entityManager ,
        CentresDeFormationRepository $centresRepository ,
        FormateursRepository $formateursRepo ,
        EtudiantRepository $etudiantRepo ,
        FormationRepository $formationRepo ,
        DateService $dateService ,
        PackFormationRepository $packFormationRepo ,
        ContenuPackFormationRepository $contenuPackRepo ,
        ContenuAbonnementRepository $contenuAbonnementRepo ,
        CreateIndemnitesService $createIndemnitesService ,
        ObtenirIndemnitesService $obtenirIndemnitesDeFormateuresService,
        SupprimerIdemnitesService $supprimerIdemnitesDeFormateursService ,
        ){
        $this->abonnementRepo = $abonnementRepo ;
        $this->entityManager = $entityManager ;
        $this->centresRepository = $centresRepository ;
        $this->formateursRepo = $formateursRepo ;
        $this->etudiantRepo = $etudiantRepo ;
        $this->formationRepo = $formationRepo ;
        $this->dateService = $dateService ;
        $this->packFormationRepo = $packFormationRepo ;
        $this->contenuPackRepo = $contenuPackRepo ;
        $this->contenuAbonnementRepo = $contenuAbonnementRepo ;
        $this->createIndemnitesService = $createIndemnitesService ;
        $this->obtenirIndemnitesDeFormateuresService = $obtenirIndemnitesDeFormateuresService ;
        $this->supprimerIdemnitesDeFormateursService = $supprimerIdemnitesDeFormateursService ;

    }

    public function getAbonnementsPack($centreId){
        try{
            $centreDeFormation = $this->centresRepository->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $abonemments = $this->abonnementRepo->findBy(['CentresDeFormation'=>$centreDeFormation , 'relatedToPack'=>true]);
            return $abonemments ;
        }catch(Exception $eroor){
            return false ;
        }   
    }


    public function getConteuAbonnement($abonnementId){
        try{
            $abonnementObject = $this->abonnementRepo->find((int) $abonnementId) ;
            if(!$abonnementObject) return false ;
            $contenus =  $this->contenuAbonnementRepo->findBy(['Abonnement'=> $abonnementObject ]);
            return $contenus ;
        }catch(Exception $eroor){
            return false ;
        }   
    }

 
    public function createAbonnementDePack ($centreId , $data ){
        try{
            $centreDeFormation = $this->centresRepository->find((int) ($centreId));
            if(!$centreDeFormation) return "centre n'existe pas" ;
            
            $abonnementObject = new Abonnement ;
            $abonnementObject->setCentresDeFormation( $centreDeFormation ) ;
            if(empty($data['Etudiant'])) return "Etudiant est obligatoire" ;
            if(empty($data['Pack'])) return "Pack de formation est obligatoire" ;
            if(empty($data['DateDebut'])) return "Date Debut est obligatoire" ;
            if(empty($data['DateFin'])) return "Date Fin est obligatoire" ;
            if(empty($data['Statut'])) return "Statut est obligatoire" ;

            $etudiant = $this->etudiantRepo->find( (int) $data['Etudiant']['id'] );
            if(!$etudiant) return "Etudiant n'existe pas" ;
            $abonnementObject->setEtudiant( $etudiant ) ;

            $packObject =  $this->packFormationRepo->find( (int)  $data['Pack']['id'] )  ;
            if(!$packObject) return "pack de formation n'existe pas" ;
            $abonnementObject->setPack($packObject) ;

            $DateDebut = new DateTimeImmutable( $data['DateDebut'] ) ;
            if(!$DateDebut) return "Date Debut n'est pas valide" ;
            $abonnementObject->setDateDebut( $DateDebut ) ;

            $DateFin = new DateTimeImmutable( $data['DateFin'] ) ;
            if(!$DateFin) return "Date fin n'est pas valide" ;
            $abonnementObject->setDateFin( $DateFin ) ;

            // Ici on va essayer de vérifier si les dates de début et de fin sont valides
            $datesValidation = $this->dateService->validerDeuxDates($abonnementObject->getDateDebut(true) , $abonnementObject->getDateFin(true));
            if(!$datesValidation) return "Les dates de début et de fin ne sont pas valides. La date de début doit être antérieure à la date de fin. " ;

            $montantAbonnement = (float) $data['MontantAbonnement'] ;
            $abonnementObject->setMontantAbonnement( $montantAbonnement ) ;

            $commentaire = (string) $data['Commentaire'] ;
            $abonnementObject->setCommentaire( $commentaire ) ;

            $abonnementObject->setStatut( (string) $data['Statut'] ) ;
    
            $abonnementObject->setCreatedAt( new DateTimeImmutable ) ;
            $abonnementObject->setRelatedToPack( true ) ;
            $this->entityManager->persist($abonnementObject);

            // Maintenant nous allons essayer d'associer l'abonnement à son pack et de créer les contenus de l'abonnement en utilisant les contenus du pack de formation

            $montantTotal = 0 ;
            $pourcentageTotal = 0 ;
            $contenusAbonnement = [] ;
            if(empty( $data['contenu'] )) return "Vous ne pouvez pas créer un abonnement de pack sans contenu !" ;
            foreach($data['contenu'] as $contenu){
                $contenuPack = $this->contenuPackRepo->find( (int) $contenu['id'] );
                if(!$contenuPack) return "Le contenu du pack que vous avez sélectionné n'existe plus !" ;
                if($contenuPack->getPackFormation() != $packObject ) return 'le contenu de pack est pas valide' ;
         
                $contenuAbonnement = new ContenuAbonnement ; 
                $contenuAbonnement->setAbonnement($abonnementObject) ;
                $contenuAbonnement->setContenu($contenuPack) ;
                $contenuAbonnement->setPourcentageDeFormateur((float) $contenu['pourcentageDeFormateur'] ) ;
                $pourcentageTotal += (float) $contenu['pourcentageDeFormateur'] ;
                $contenuAbonnement->setMontantDeFormateur((float) $contenu['montantDeFormateur'] ) ;
                $montantTotal += (float) $contenu['montantDeFormateur'] ;

                // Le pourcentage et le montant de formateur du contenu d'abonnement doivent être exacts par rapport à l'abonnement.
                if( ( ( $contenuAbonnement->getPourcentageDeFormateur() * $abonnementObject->getMontantAbonnement() ) / 100 ) != $contenuAbonnement->getMontantDeFormateur()  ) return "Le montant du formateur n'est pas exact par rapport au pourcentage !" ;   
                ///////////////////////////////////////////////////
                $contenuAbonnement->setCreatedAt( new DateTimeImmutable ) ;
                $this->entityManager->persist($contenuAbonnement);

                // On va utiliser cette variable après pour créer les indemnités des formateurs depuis le contenu d'abonnement
                $contenusAbonnement [] = $contenuAbonnement ;
                ////////

            }

            // Le total des pourcentages des contenus de cet abonnement ne doit pas dépasser 100.
            if($pourcentageTotal > 100 ) return "Le total des pourcentages des contenus de cet abonnement ne doit pas dépasser 100 !" ;
            /////////////////////////////////////////// 
            // Le total des montants des formateurs des contenus de cet abonnement ne doit pas dépasser le montant de l'abonnement.
            if($montantTotal >  $abonnementObject->getMontantAbonnement()  ) return "Le total des montants des formateurs des contenus de cet abonnement ne doit pas dépasser le montant de l'abonnement ! " ;
            /////////////////////////////////////////// 



            // Après la création de l'abonnement, il faut créer les indemnités de les formateures
            $isIndemniteeCreated = $this->createIndemnitesService->createIndemniteFromAbonnementDePack($abonnementObject , $contenusAbonnement) ;
            if( $isIndemniteeCreated != true  ) return "une erreure est survenue l'ors la creation de l'indemnite" ;
            ///////////////////////

            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }


                 
    public function updateAbonnementDePack ( $data ){
        try{
            $abonnementObject = $this->abonnementRepo->find((int) $data['id']);
            if(!$abonnementObject) return "abonnement n'existe pas" ;

            // Cette fonction a été créée seulement pour modifier les abonnements de pack, alors si l'abonnement n'est pas associé à un pack, on va retourner un message d'erreur
            if(!$abonnementObject->isRelatedToPack()) return "ce abonnement est pas d'un pack de formation" ;
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // Si l'abonnement est déjà payé, alors on ne peut pas le modifier.
            if($abonnementObject->getMontantPayee()) return "L'abonnement est déjà payé, vous ne pouvez donc pas le modifier." ;
            //////////////////////////////////////////////////////////////////
            
            // Avant de modifier un abonnement, il faut s'assurer que les indemnités des formateurs associées à cet abonnement ne sont pas encore payées
            $indemnitesDeFormateurs = $this->obtenirIndemnitesDeFormateuresService->obtenirIndemnitesParAbonnement($abonnementObject);
            if( $indemnitesDeFormateurs === false) return 'Une erreur est survenue.' ;
            foreach ( $indemnitesDeFormateurs as $indemnite){
                if($indemnite->getMontantPayee()) return "Vous ne pouvez pas modifier cet abonnement car vous avez déjà payé un formateur en se basant sur cet abonnement" ;
            }
            ////////////////////////


            if(empty($data['Etudiant'])) return "Etudiant est obligatoire" ;
            if(empty($data['Pack'])) return "Pack de formation est obligatoire" ;
            if(empty($data['DateDebut'])) return "Date Debut est obligatoire" ;
            if(empty($data['DateFin'])) return "Date Fin est obligatoire" ;
            if(empty($data['Statut'])) return "Statut est obligatoire" ;


            if($abonnementObject->getEtudiant()?->getId() !=  (int) $data['Etudiant']['id'] ){
                $etudiant = $this->etudiantRepo->find( (int) $data['Etudiant']['id'] );
                if(!$etudiant) return "Etudiant n'existe pas" ;
                $abonnementObject->setEtudiant( $etudiant ) ;
            }

            if($abonnementObject->getPack()?->getId() !=  (int) $data['Pack']['id'] ){
                $packObject =  $this->packFormationRepo->find( (int)  $data['Pack']['id'] )  ;
                if(!$packObject) return "pack de formation n'existe pas" ;
                // Dans l'abonnement de pack, on ne modifie pas le pack de formation associé à cet abonnement.
                // $abonnementObject->setPack($packObject) ;
            
            }

            if($abonnementObject->getDateDebut() != $data['DateDebut']){
                $DateDebut = new DateTimeImmutable( $data['DateDebut'] ) ;
                if(!$DateDebut) return "Date Debut n'est pas valide" ;
                $abonnementObject->setDateDebut( $DateDebut ) ;
            }

            if($abonnementObject->getDateFin() != $data['DateFin']){
                $DateFin = new DateTimeImmutable( $data['DateFin'] ) ;
                if(!$DateFin) return "Date Fin n'est pas valide" ;
                $abonnementObject->setDateFin( $DateFin ) ;
            }

            // Ici on va essayer de vérifier si les dates de début et de fin sont valides
            $datesValidation = $this->dateService->validerDeuxDates($abonnementObject->getDateDebut(true) , $abonnementObject->getDateFin(true));
            if(!$datesValidation) return "Les dates de début et de fin ne sont pas valides. La date de début doit être antérieure à la date de fin. " ;
           
            if($abonnementObject->getMontantAbonnement() != (float) $data['MontantAbonnement'] ){
                $montantAbonnement = (float) $data['MontantAbonnement'] ;
                $abonnementObject->setMontantAbonnement( $montantAbonnement ) ;
            }
            if($abonnementObject->getCommentaire() != (string) $data['Commentaire'] ){
                $commentaire = (string) $data['Commentaire'] ;
                $abonnementObject->setCommentaire( $commentaire ) ;
            }

            $abonnementObject->setStatut( (string) $data['Statut']  ) ;
            $abonnementObject->setUpdatedAt(new DateTimeImmutable);
            $this->entityManager->persist($abonnementObject);


            // Maintenant nous allons essayer de modifier les contenu d'abonnement pour l'abonnement 
            $montantTotal = 0 ;
            $pourcentageTotal = 0 ;
            if(empty( $data['contenuAbonnement'] )) return "Vous ne pouvez pas modifier un abonnement de pack sans contenu !" ;
           
            //  avant la boucle ,il faut s'assurer que tous les contenu de l'abonnement qui on existe actuelement dans la base de donnees , sont existe dans le tebeleau $data['contenuAbonnement'] , si non on retourner un message d'ereure .
            $allContenusDeAbonnement = $this->contenuAbonnementRepo->findBy(['Abonnement'=>$abonnementObject]) ;
            foreach ($allContenusDeAbonnement as $element){
                $verification = false ;
                foreach ($data['contenuAbonnement'] as $item){    
                    if($element->getId() == $item['id']) $verification = true ;
                }
                if(!$verification) return "l faut que vous modifiiez tout le contenu d'abonnement." ; 
            }
            //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $contenusAbonnement = [] ;
            foreach($data['contenuAbonnement'] as $contenu){

                $contenuAbonnement = $this->contenuAbonnementRepo->find((int) $contenu['id']) ; 
                if(!$contenuAbonnement) return "Le contenu d'abonnement' que vous avez sélectionné n'existe plus !" ;
                if($contenuAbonnement->getAbonnement() != $abonnementObject ) return "le contenu de d'abonnement selectionne est pas valide" ;

                $contenuAbonnement->setPourcentageDeFormateur((float) $contenu['pourcentageDeFormateur'] ) ;
                $pourcentageTotal += $contenuAbonnement->getPourcentageDeFormateur() ;
                $contenuAbonnement->setMontantDeFormateur((float) $contenu['montantDeFormateur'] ) ;
                $montantTotal += $contenuAbonnement->getMontantDeFormateur() ;

                // Le pourcentage et le montant de formateur du contenu d'abonnement doivent être exacts par rapport à l'abonnement.
                if( ( ( $contenuAbonnement->getPourcentageDeFormateur() * $abonnementObject->getMontantAbonnement() ) / 100 ) != $contenuAbonnement->getMontantDeFormateur()  ) return "Le montant du formateur n'est pas exact par rapport au pourcentage !" ;   
                ///////////////////////////////////////////////////
                $contenuAbonnement->setUpdatedAt( new DateTimeImmutable ) ;
                $this->entityManager->persist($contenuAbonnement);

                // On va utiliser cette variable après pour créer les indemnités des formateurs depuis le contenu d'abonnement
                $contenusAbonnement [] =  $contenuAbonnement  ;
                ////////////////////////////////////////////////////////////////////////////////////

            }

            // Le total des pourcentages des contenus de cet abonnement ne doit pas dépasser 100.
            if($pourcentageTotal > 100 ) return "Le total des pourcentages des contenus de cet abonnement ne doit pas dépasser 100 !" ;
            /////////////////////////////////////////// 
            // Le total des montants des formateurs des contenus de cet abonnement ne doit pas dépasser le montant de l'abonnement.
            if($montantTotal >  $abonnementObject->getMontantAbonnement()  ) return "Le total des montants des formateurs des contenus de cet abonnement ne doit pas dépasser le montant de l'abonnement ! " ;
            /////////////////////////////////////////// 

            
            // Si tout se passe bien, on va supprimer toutes les indemnités de cet abonnement et les régénérer
            $indemnitesDeleted = $this->supprimerIdemnitesDeFormateursService->SupprimerIdemnites($indemnitesDeFormateurs);
            /////////////////////////////////////////
            // ici on vas regenerer une indemnité pour les formateurs
            $isIndemniteeCreated = $this->createIndemnitesService->createIndemniteFromAbonnementDePack($abonnementObject , $contenusAbonnement) ;
            if( $isIndemniteeCreated != true  ) return "une erreure est survenue l'ors la creation de l'indemnite" ;
            ///////////////////////////////////////////////////////
           
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
    }
    
    public function deleteAbonnementDePack ($id){
        try{
            $abonnementObject = $this->abonnementRepo->find((int) $id ) ;
            if(!$abonnementObject) return "L'abonnement n'existe plus" ;
                        
            // Si l'abonnement est déjà payé, alors on ne peut pas le supprimer.
            if($abonnementObject->getMontantPayee()) return "L'abonnement est déjà payé, vous ne pouvez donc pas le supprimer." ;
            //////////////////////////////////////////////////////////////////
 
            // Avant de supprimer un abonnement, il faut s'assurer que les indemnités des formateurs associées à cet abonnement ne sont pas encore payées
            $indemnitesDeFormateures = $this->obtenirIndemnitesDeFormateuresService->obtenirIndemnitesParAbonnement($abonnementObject);
            if( $indemnitesDeFormateures === false) return 'Une erreur est survenue.' ;
            foreach ( $indemnitesDeFormateures as $indemnite){
                if($indemnite->getMontantPayee()) return "Vous ne pouvez pas supprimer cet abonnement car vous avez déjà payé un formateur en se basant sur cet abonnement" ;
            }
            // Si tout est OK, l'abonnement sera supprimé et les indemnités des formateurs seront supprimées automatiquement, 
            // car dans l'entité "IndemnitesDeFormateurs" nous avons utilisé <<onDelete: 'CASCADE'>>
            ////////////////////////

            $this->entityManager->remove($abonnementObject);
            $this->entityManager->flush();

            return true ;
        }catch(Exception $e){
            return "'Une erreur est survenue.'" ;
        }
    }


}


