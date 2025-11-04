<?php

namespace App\Service\IndemnitesDeFormateuresServices ;

use DateTime;
use Exception;
use DateTimeImmutable;
use App\Entity\Abonnement;
use App\Entity\IndemnitesDeFormateures;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;

class CreateIndemnitesService {
    public function __construct(EntityManagerInterface $entityManager , AbonnementRepository $abonnementRepo){
        $this->entityManager = $entityManager ;  
        $this->abonnementRepo = $abonnementRepo ; 
    }

    public function createIndemniteFromAbonnementNormal(Abonnement $abonnementObject){

        try{
            $indemniteeObject = new IndemnitesDeFormateures ; 

            $indemniteeObject->setFormateur($abonnementObject->getFormateur()) ;
            $indemniteeObject->setMontantDeFormateur($abonnementObject->getMontantFormateur()) ;
            $indemniteeObject->setAbonnement($abonnementObject) ;
            $indemniteeObject->setCentreDeFormation($abonnementObject->getCentresDeFormation()) ;
            $indemniteeObject->setCreatedAt( new DateTimeImmutable ) ;
            $this->entityManager->persist($indemniteeObject);
            $this->entityManager->flush();
            return true ;

        }catch(Exception $e){
            return false ;
        }
    }


    public function createIndemniteFromAbonnementDePack(Abonnement $abonnementObject , $contenusAbonnement){

        try{
            foreach($contenusAbonnement as $contenuAbonnement){
                $indemniteeObject = new IndemnitesDeFormateures ; 
                $indemniteeObject->setMontantDeFormateur($contenuAbonnement->getMontantDeFormateur()) ;
                $indemniteeObject->setFormateur($contenuAbonnement->getContenu()?->getFormateur()) ;
                $indemniteeObject->setAbonnement($abonnementObject) ;
                $indemniteeObject->setCentreDeFormation($abonnementObject->getCentresDeFormation()) ;
                $indemniteeObject->setCreatedAt( new DateTimeImmutable ) ;
                $this->entityManager->persist($indemniteeObject);
            }
            $this->entityManager->flush();
            return true ;

        }catch(Exception $e){
            return false ;
        }
    }

    
}