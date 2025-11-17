<?php

namespace App\Service\RevenusDuCentreSrvices ;

use Exception;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RevenusDuCentreRepository;

class SupprimerRevenusService {
    public function __construct(EntityManagerInterface $entityManager , RevenusDuCentreRepository $revenusDuCentreRepo ) {
        $this->entityManager = $entityManager ; 
        $this->revenusDuCentreRepo = $revenusDuCentreRepo ; 
    }

    public function supprimerRevenu($revenuId){

        try{
            $revenuObject = $this->revenusDuCentreRepo->find($revenuId) ;
            if(!$revenuObject) return "Le revenu que vous avez sélectionnée n'existe pas." ;

            $this->entityManager->remove($revenuObject);
            $this->entityManager->flush();

            return true ;

        }catch(Exception $e){
            return "Une erreur est survenue." ;
        }
    }


    



}