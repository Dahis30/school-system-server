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
use App\Repository\CentresDeFormationRepository;
use App\Repository\ContenuPackFormationRepository;

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

 
    public function createAbonnementDePack ($centreId , $data ){
        try{
            $centreDeFormation = $this->centresRepository->find((int) ($centreId));
            if(!$centreDeFormation) return "centre n'existe pas" ;
            
            $abonnementObject = new Abonnement ;
            $abonnementObject->setCentresDeFormation( $centreDeFormation ) ;
            if(empty($data['Etudiant'])) return "Etudiant est obligatoire" ;
            if(empty($data['Pack'])) return "Etudiant est obligatoire" ;
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

            }

            // Le total des pourcentages des contenus de cet abonnement ne doit pas dépasser 100.
            if($pourcentageTotal > 100 ) return "Le total des pourcentages des contenus de cet abonnement ne doit pas dépasser 100 !" ;
            /////////////////////////////////////////// 
            // Le total des montants des formateurs des contenus de cet abonnement ne doit pas dépasser le montant de l'abonnement.
            if($montantTotal >  $abonnementObject->getMontantAbonnement()  ) return "Le total des montants des formateurs des contenus de cet abonnement ne doit pas dépasser le montant de l'abonnement ! " ;
            /////////////////////////////////////////// 

            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }


}


