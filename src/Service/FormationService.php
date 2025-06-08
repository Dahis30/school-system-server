<?php

namespace App\Service ;

use DateTimeImmutable;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;

class FormationService {

    public function __construct(FormationRepository $formationRepo ,  CentresDeFormationRepository $centresRepository , EntityManagerInterface $entityManager ){
        $this->formationRepo = $formationRepo ;
        $this->centresRepository = $centresRepository ;  
        $this->entityManager = $entityManager ;

    }

    public function getFormationsByCentre($centreId){
        try{
            $centreDeFormation = $this->centresRepository->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $formations = $this->formationRepo->findBy(['CentreDeFormation'=>$centreDeFormation]);
            return  $formations ;
        }catch(Exception $e){
            return false ;
        }
    }




    public function createFormation ($centreId , $data ){
        try{
            $centreDeFormation = $this->centresRepository->find((int) ($centreId));
            if(!$centreDeFormation) return "centre n'existe pas" ;
            $formation = new Formation ;
            $formation->setCentreDeFormation( $centreDeFormation ) ;
            if(empty($data['titre'])) return "titre obligatoire" ;
            $formation->setTitre((string) $data['titre']);
            $formation->setDescription((string) $data['description']);
            $formation->setCreatedAt(new DateTimeImmutable);
            $this->entityManager->persist($formation);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }

    
                
    public function updateFormation ( $data ){
        try{
            $formation = $this->formationRepo->find((int) $data['id']);
            if(!$formation) return "formation n'existe pas" ;
            if(empty($data['titre'])) return "titre obligatoire" ;
            $formation->setTitre($data['titre']);
            $formation->setDescription($data['description']);
            $formation->setUpdatedAt(new DateTimeImmutable);
            $this->entityManager->persist($formation);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
    }


    
    public function deleteFormation ($id){
        try{
            $formation = $this->formationRepo->find((int) $id ) ;
            $this->entityManager->remove($formation);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return false ;
        }
      
    }




}