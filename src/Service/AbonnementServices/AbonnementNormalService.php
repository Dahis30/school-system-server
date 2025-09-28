<?php

namespace App\Service\AbonnementServices ;

use Exception;
use DateTimeImmutable;
use App\Entity\Abonnement;
use App\Repository\EtudiantRepository;
use App\Repository\FormationRepository;
use App\Repository\AbonnementRepository;
use App\Repository\FormateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DateServices\DateService;
use App\Repository\CentresDeFormationRepository;


class AbonnementNormalService{

    public function __construct(
        AbonnementRepository $abonnementRepo ,
        EntityManagerInterface $entityManager ,
        CentresDeFormationRepository $centresRepository ,
        FormateursRepository $formateursRepo ,
        EtudiantRepository $etudiantRepo ,
        FormationRepository $formationRepo ,
        DateService $dateService ,
        ){
        $this->abonnementRepo = $abonnementRepo ;
        $this->entityManager = $entityManager ;
        $this->centresRepository = $centresRepository ;
        $this->formateursRepo = $formateursRepo ;
        $this->etudiantRepo = $etudiantRepo ;
        $this->formationRepo = $formationRepo ;
        $this->dateService = $dateService ;

    }

    public function getAbonnements($centreId){
        try{
            $centreDeFormation = $this->centresRepository->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $abonemments = $this->abonnementRepo->findBy(['CentresDeFormation'=>$centreDeFormation , 'relatedToPack' =>false]);
            return $abonemments ;
        }catch(Exception $eroor){
            return false ;
        }
    }



    
    public function createAbonnementNormal ($centreId , $data ){
        try{
            $centreDeFormation = $this->centresRepository->find((int) ($centreId));
            if(!$centreDeFormation) return "centre n'existe pas" ;
            $abonnementObject = new Abonnement ;
            $abonnementObject->setCentresDeFormation( $centreDeFormation ) ;
            if(empty($data['Formateur'])) return "Formateur est obligatoire" ;
            if(empty($data['Etudiant'])) return "Etudiant est obligatoire" ;
            if(empty($data['Formation'])) return "Formation est obligatoire" ;
            if(empty($data['DateDebut'])) return "Date Debut est obligatoire" ;
            if(empty($data['DateFin'])) return "Date Fin est obligatoire" ;
            if(empty($data['Statut'])) return "Statut est obligatoire" ;

            $Formateur = $this->formateursRepo->find( (int) $data['Formateur']['id']) ;
            if(!$Formateur) return "Formateur n'existe pas" ;
            $abonnementObject->setFormateur( $Formateur ) ;

            $etudiant = $this->etudiantRepo->find( (int) $data['Etudiant']['id'] );
            if(!$etudiant) return "Etudiant n'existe pas" ;
            $abonnementObject->setEtudiant( $etudiant ) ;

            $formation = $this->formationRepo->find( (int) $data['Formation']['id'] ) ;
            if(!$formation) return "Formation n'existe pas" ;
            $abonnementObject->setFormation( $formation ) ;

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

            $montantFormateur = (float) $data['MontantFormateur'] ;
            $abonnementObject->setMontantFormateur( $montantFormateur ) ;

            $commentaire = (string) $data['Commentaire'] ;
            $abonnementObject->setCommentaire( $commentaire ) ;

            $abonnementObject->setStatut( (string) $data['Statut']  ) ;

            $abonnementObject->setCreatedAt( new DateTimeImmutable ) ;
            $abonnementObject->setRelatedToPack( false ) ;
            
            $this->entityManager->persist($abonnementObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }





               
    public function updateAbonnementNormal ( $data ){
        try{
            $abonnementObject = $this->abonnementRepo->find((int) $data['id']);
            if(!$abonnementObject) return "abonnement n'existe pas" ;

            // Cette fonction a été créée seulement pour modifier les abonnements normal , alors si l'abonnement est associé à un pack, on va retourner un message d'erreur .
            if($abonnementObject->isRelatedToPack()) return "ce abonnement est d'un pack de formation" ;
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            
            // Si l'abonnement est déjà payé, alors on ne peut pas le modifier.
            if($abonnementObject->getMontantPayee()) return "L'abonnement est déjà payé, vous ne pouvez donc pas le modifier." ;
            //////////////////////////////////////////////////////////////////
            
            if(empty($data['Formateur'])) return "Formateur est obligatoire" ;
            if(empty($data['Etudiant'])) return "Etudiant est obligatoire" ;
            if(empty($data['Formation'])) return "Formation est obligatoire" ;
            if(empty($data['DateDebut'])) return "Date Debut est obligatoire" ;
            if(empty($data['DateFin'])) return "Date Fin est obligatoire" ;
            if(empty($data['Statut'])) return "Statut est obligatoire" ;


            if($abonnementObject->getFormateur()?->getId() !=  (int) $data['Formateur']['id'] ){
                $Formateur = $this->formateursRepo->find( (int) $data['Formateur']['id']) ;
                if(!$Formateur) return "Formateur n'existe pas" ;
                $abonnementObject->setFormateur( $Formateur ) ;
            }

            if($abonnementObject->getEtudiant()?->getId() !=  (int) $data['Etudiant']['id'] ){
                $etudiant = $this->etudiantRepo->find( (int) $data['Etudiant']['id'] );
                if(!$etudiant) return "Etudiant n'existe pas" ;
                $abonnementObject->setEtudiant( $etudiant ) ;
            }

            if($abonnementObject->getFormation()?->getId() !=  (int) $data['Formation']['id'] ){
                $formation = $this->formationRepo->find( (int) $data['Formation']['id'] ) ;
                if(!$formation) return "Formation n'existe pas" ;
                $abonnementObject->setFormation( $formation ) ;
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

            if($abonnementObject->getMontantFormateur() != (float) $data['MontantFormateur'] ){
                $montantFormateur = (float) $data['MontantFormateur'] ;
                $abonnementObject->setMontantFormateur( $montantFormateur ) ;
            }

            if($abonnementObject->getCommentaire() != (string) $data['Commentaire'] ){
                $commentaire = (string) $data['Commentaire'] ;
                $abonnementObject->setCommentaire( $commentaire ) ;
            }

            $abonnementObject->setStatut( (string) $data['Statut']  ) ;

            $abonnementObject->setUpdatedAt(new DateTimeImmutable);

            $this->entityManager->persist($abonnementObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
    }
       


    
    
    public function deleteAbonnement ($id){
        try{
            $abonnementObject = $this->abonnementRepo->find((int) $id ) ;
            if(!$abonnementObject) return "L'abonnement n'existe plus" ;
                        
            // Si l'abonnement est déjà payé, alors on ne peut pas le supprimer.
            if($abonnementObject->getMontantPayee()) return "L'abonnement est déjà payé, vous ne pouvez donc pas le supprimer." ;
            //////////////////////////////////////////////////////////////////
            
            $this->entityManager->remove($abonnementObject);
            $this->entityManager->flush();

            return true ;
        }catch(Exception $e){
            return "'Une erreur est survenue.'" ;
        }
    }





}

