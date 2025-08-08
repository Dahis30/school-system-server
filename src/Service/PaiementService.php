<?php

namespace App\Service ;

use Exception;
use DateTimeImmutable;
use App\Entity\Paiement;
use App\Entity\Abonnement;
use App\Repository\EtudiantRepository;
use App\Repository\PaiementRepository;
use App\Repository\FormationRepository;
use App\Repository\AbonnementRepository;
use App\Repository\FormateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DateServices\DateService;
use App\Repository\CentresDeFormationRepository;


class PaiementService{

    public function __construct(
        PaiementRepository $paiementRepo ,
        AbonnementRepository $abonnementRepo ,
        EntityManagerInterface $entityManager ,
        CentresDeFormationRepository $centresRepository ,
        FormateursRepository $formateursRepo ,
        EtudiantRepository $etudiantRepo ,
        FormationRepository $formationRepo ,
        DateService $dateService ,
        ){
        $this->paiementRepo = $paiementRepo ;
        $this->abonnementRepo = $abonnementRepo ;
        $this->entityManager = $entityManager ;
        $this->centresRepository = $centresRepository ;
        $this->formateursRepo = $formateursRepo ;
        $this->etudiantRepo = $etudiantRepo ;
        $this->formationRepo = $formationRepo ;
        $this->dateService = $dateService ;

    }

    public function getPaiementsByCentre($centreId){
        try{
            $centreDeFormation = $this->centresRepository->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $paiements = $this->paiementRepo->findBy(['CentreDeFormation'=>$centreDeFormation]);
            return $paiements ;
        }catch(Exception $eroor){
            return false ;
        }
    }


    
    public function getPaiementsByAbonnement($abonnementId){
        try{
            $abonnement = $this->abonnementRepo->find((int) $abonnementId);
            if(!$abonnement) return false ;
            $paiements = $this->paiementRepo->findBy(['Abonnement'=>$abonnement]);
            return $paiements ;
        }catch(Exception $eroor){
            return false ;
        }
    }

    
    public function createPaiement ( $data ){
        try{
            
            if(empty($data['montant']) || (float) $data['montant'] <= 0 ) return "Le montant a paye est invalide" ;
            if(empty($data['Abonnement'])) return "Abonnement est obligatoire" ;
            if(empty($data['modePaiement'])) return "modePaiement est obligatoire" ;
            if(empty($data['datePaiement'])) return "Date paiement est obligatoire" ;

            $paiementObject = new Paiement ;

            
            $paiementObject->setMontant( (float) $data['montant'] ) ;
            $paiementObject->setModePaiement( (string) $data['modePaiement'] ) ;

            $abonnement = $this->abonnementRepo->find( (int) $data['Abonnement']['id']) ;
            if(!$abonnement) return "Abonnement n'existe pas" ;
            $newMontanPayee = $abonnement->getMontantPayee() + $paiementObject->getMontant() ;
            if($newMontanPayee > $abonnement->getMontantAbonnement() )  return " Le montant de paiement n'est pas valide par rapport au reste à payer dans l'abonnement. " ;
            $abonnement->setMontantPayee($newMontanPayee) ;
            $this->entityManager->persist($abonnement);
            $paiementObject->setAbonnement( $abonnement ) ;

            $centreDeFormation = $abonnement->getCentresDeFormation();  
            if(!$centreDeFormation) return "centre n'existe pas" ;
            $paiementObject->setCentreDeFormation( $centreDeFormation ) ;


            $datePaiement = new DateTimeImmutable( $data['datePaiement'] ) ;
            if(!$datePaiement) return "Date paiement n'est pas valide" ;
            $paiementObject->setDatePaiement( $datePaiement ) ;

            $paiementObject->setCommentaire( $data['commentaire'] ) ;
            $paiementObject->setCreatedAt( new DateTimeImmutable ) ;
            
            $this->entityManager->persist($paiementObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }

    
    public function deletePaiement ($id){
        try{
            $paiementObject = $this->paiementRepo->find((int) $id ) ;
            if(!$paiementObject) return false ;
            $abonnementObject =  $paiementObject->getAbonnement() ;
            if(!$abonnementObject) return false ;

            $newMontanPayee = $abonnementObject->getMontantPayee() - $paiementObject->getMontant() ;
            $abonnementObject->setMontantPayee($newMontanPayee) ;
            $this->entityManager->persist($abonnementObject);

            $this->entityManager->remove($paiementObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return false ;
        }
      
    }





}

