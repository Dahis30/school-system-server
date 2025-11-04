<?php

namespace App\Service\PackDeFormationsServices ;

use Exception;
use DateTimeImmutable;
use App\Entity\PackFormation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PackFormationRepository;
use App\Repository\CentresDeFormationRepository;

class PackDeFormationsService {
    public function __construct(PackFormationRepository $packFormationRepo , CentresDeFormationRepository $centreDeFormationRepo , EntityManagerInterface $entityManager){
        $this->packFormationRepo = $packFormationRepo ;
        $this->centreDeFormationRepo = $centreDeFormationRepo ;
        $this->entityManager = $entityManager ;
    }


    public function getPacksByCentre ( int $centreId){
        try{
            $centreDeFormation = $this->centreDeFormationRepo->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $packsDeFormations = $this->packFormationRepo->findBy(['CentreDeFormation'=>$centreDeFormation]);
            return $packsDeFormations ;
        }catch(Exception $eroor){
            return false ;
        }
    }
     
    public function createPack ($centreId , $data ){
        try{
            
            $centreDeFormation = $this->centreDeFormationRepo->find((int) ($centreId));
            if(!$centreDeFormation) return "centre n'existe pas" ;
            $packObject = new PackFormation ;
            $packObject->setCentreDeFormation( $centreDeFormation ) ;

            if(empty($data['titre'])) return "titre est obligatoire" ;
            if(( (float) $data['prix'] <= 0 )) return "Le prix n'est pas valide, il doit être supérieur à 0." ;
            
            $titre = (string) $data['titre'] ;
            $packObject->setTitre( $titre ) ;

            $description = (string) $data['description'] ;
            $packObject->setDescription( $description ) ;

            $prix = (float) $data['prix'] ;
            $packObject->setPrix( $prix ) ;

            $packObject->setCreatedAt( new DateTimeImmutable ) ;
            
            $this->entityManager->persist($packObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }
    
               
    public function updatePack ( $data ){
        try{
            $packObject = $this->packFormationRepo->find((int) $data['id']);
            if(!$packObject) return "abonnement n'existe pas" ;

            if(empty($data['titre'])) return "Le titre est obligatoire" ;
            if(( (float) $data['prix'] <= 0 )) return "Le prix n'est pas valide, il doit être supérieur à 0." ;

            $titre = (string) $data['titre'] ;
            $packObject->setTitre( $titre ) ;

            $description = (string) $data['description'] ;
            $packObject->setDescription( $description ) ;

            $prix = (float) $data['prix'] ;
            $packObject->setPrix( $prix ) ;

            $packObject->setUpdatedAt(new DateTimeImmutable);

            $this->entityManager->persist($packObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
    }

    public function deletePck ($id){
        try{
            $packObject = $this->packFormationRepo->find((int) $id ) ;
            if(!$packObject) return false ;
            $this->entityManager->remove($packObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return false ;
        }
    }

     




}