<?php

namespace App\Service\RevenusDuCentreSrvices\RevenusAbonnements ;

use Exception;
use DateTimeImmutable;
use App\Entity\RevenusDuCentre;
use App\Repository\PaiementRepository;
use App\Repository\RevenusDuCentreRepository;
use App\Repository\CentresDeFormationRepository;

class ObtenirRevenusAbonnementsService {
    public function __construct( CentresDeFormationRepository $centresDeFormationRepo , RevenusDuCentreRepository $revenusDuCentreRepo , PaiementRepository $paiementRepository ) {
        $this->centresDeFormationRepo = $centresDeFormationRepo ; 
        $this->revenusDuCentreRepo = $revenusDuCentreRepo ; 
        $this->paiementRepository = $paiementRepository ; 
    }

    public function obtenirRevenusDesAbonnements( $centreId ){
        // Dans cette fonction, on va essayer de construire des revenus de l'entité RevenusDuCentre en se basant sur les paiements des abonnements
        try{
            $centreObject = $this->centresDeFormationRepo->find($centreId) ;
            if(!$centreObject) return false ;

            $revenusList = [] ;
            $paiements = $this->paiementRepository->findBy(['CentreDeFormation'=>$centreObject]) ;
            foreach($paiements as $paiementObject){
                $revenuObject = new RevenusDuCentre  ;
                $revenuObject->setMontant($paiementObject->getMontant()) ;
                $revenuObject->setDescription($paiementObject->getCommentaire() ?: "Paiement d'abonnement") ;
                $revenuObject->setDate($paiementObject->getDatePaiement(true)) ;
                $revenuObject->setCreatedAt( new DateTimeImmutable) ;
                $revenusList[] = $revenuObject;            
            }
            return $revenusList ;
        }catch(Exception $eroor){
            return false ;
        }
    }


}