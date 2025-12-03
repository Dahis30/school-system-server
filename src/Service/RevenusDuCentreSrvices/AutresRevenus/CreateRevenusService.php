<?php

namespace App\Service\RevenusDuCentreSrvices\AutresRevenus ;

use Exception;
use DateTimeImmutable;
use App\Entity\RevenusDuCentre;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;

class CreateRevenusService {
    public function __construct( CentresDeFormationRepository $centresDeFormationRepo , EntityManagerInterface $entityManager ,  ) {
        $this->centresDeFormationRepo = $centresDeFormationRepo ; 
        $this->entityManager = $entityManager ; 
    }
    
    public function createRevenus($data){

        try{
            $centreObject = $this->centresDeFormationRepo->find($data['centreObject']['id']) ;
            if(!$centreObject) return "Le centre que vous avez sélectionnée n'existe pas." ;
              
            $revenuObject = new RevenusDuCentre ;
            $revenuObject->setCentreDeFormation($centreObject) ;
            $revenuObject->setMontant((float)$data['montant']) ;
            $date = new DateTimeImmutable($data['date']);
            $revenuObject->setDate($date) ;
            $revenuObject->setDescription($data['description']) ;
            $revenuObject->setCommentaire($data['commentaire']) ;
            $revenuObject->setCreatedAt( new DateTimeImmutable ) ;

            $this->entityManager->persist($revenuObject);
            $this->entityManager->flush();

            return true ;

        }catch(Exception $e){
            return "Une erreur est survenue." ;
        }
    }



}