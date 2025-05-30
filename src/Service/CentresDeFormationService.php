<?php

namespace App\Service ;

use DateTime;
use DateTimeImmutable;
use App\Entity\CentresDeFormation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;

class CentresDeFormationService {
    public function __construct(EntityManagerInterface $entityManager , CentresDeFormationRepository $centresRepository){
        $this->entityManager = $entityManager ;
        $this->centresRepository = $centresRepository ;
        
    }
    

    public function getCentresDeFormation ($utilisateur){
        try{
            $centresData =  $this->centresRepository->findBy(['utilisateur'=>$utilisateur]) ;
            return $centresData ;
        }catch(Exception $e){
            return false ;

        }
    }


    public function createCentreDeFormation ($data ,$utilisateur){
        try{
            $centre = new CentresDeFormation ;
            $centre->setNom($data['nom']);
            $centre->setAdresse($data['adresse']);
            $centre->setCreatedAt(new DateTimeImmutable);
            $centre->setUtilisateur($utilisateur);
            $this->entityManager->persist($centre);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return false ;

        }
      
    }

}