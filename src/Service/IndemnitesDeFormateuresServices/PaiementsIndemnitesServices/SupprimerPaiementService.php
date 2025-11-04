<?php

namespace App\Service\IndemnitesDeFormateuresServices\PaiementsIndemnitesServices ;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PaiementsDesFormateursRepository;

class SupprimerPaiementService {
    public function __construct(EntityManagerInterface $entityManager , PaiementsDesFormateursRepository $paiementsDesFormateursRepo , ){
        $this->entityManager = $entityManager ;  
        $this->paiementsDesFormateursRepo = $paiementsDesFormateursRepo ;  
    }

    public function supprimerPaiement($paiementId){

        try{
            $paiementObject = $this->paiementsDesFormateursRepo->find($paiementId) ;
            if(!$paiementObject) return "Le paiement que vous avez sélectionnée n'existe pas." ;
        
            $indemniteObject = $paiementObject->getIndemniteDeFormateur() ; 
            $indemniteObject->setMontantPayee( $indemniteObject->getMontantPayee() -  $paiementObject->getMontant() ) ;

            $this->entityManager->persist($indemniteObject);
            $this->entityManager->remove($paiementObject);
            $this->entityManager->flush();

            return true ;

        }catch(Exception $e){
            return "Une erreur est survenue." ;
        }
    }


    
}