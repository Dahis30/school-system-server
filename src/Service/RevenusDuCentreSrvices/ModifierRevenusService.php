<?php

namespace App\Service\RevenusDuCentreSrvices ;

use Exception;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RevenusDuCentreRepository;

class ModifierRevenusService {
    public function __construct(EntityManagerInterface $entityManager , RevenusDuCentreRepository $revenusDuCentreRepo ) {
        $this->entityManager = $entityManager ; 
        $this->revenusDuCentreRepo = $revenusDuCentreRepo ; 
    }
    
    public function modifierRevenu($data){

        try{
            $revenuObject = $this->revenusDuCentreRepo->find($data['id']) ;
            if(!$revenuObject) return "Le revenu que vous avez sélectionnée n'existe pas." ;

            $revenuObject->setMontant((float)$data['montant']) ;
            $date = new DateTimeImmutable($data['date']);
            $revenuObject->setDate($date) ;
            $revenuObject->setDescription($data['description']) ;
            $revenuObject->setCommentaire($data['commentaire']) ;
            $revenuObject->setUpdatedAt( new DateTimeImmutable ) ;

            $this->entityManager->persist($revenuObject);
            $this->entityManager->flush();

            return true ;

        }catch(Exception $e){
            return "Une erreur est survenue." ;
        }
    }



}