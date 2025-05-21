<?php

namespace App\Service ;

use DateTimeImmutable;
use App\Entity\Formateurs;
use App\Repository\FormateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;


class FormateursService {
        public function __construct(EntityManagerInterface $entityManager , FormateursRepository $formateursRepository , CentresDeFormationRepository $centresRepository){
            $this->entityManager = $entityManager ;
            $this->formateursRepository = $formateursRepository ; 
            $this->centresRepository = $centresRepository ;  
        }   


        public function getformateurs ($centreId){
            try{
                $centreDeFormation = $this->centresRepository->find((int) ($centreId));
                if(!$centreDeFormation) return false ;
                $formateurs =  $this->formateursRepository->findBy(['CentreDeFormation'=>$centreDeFormation]) ;
                return $formateurs ;
            }catch(Exception $e){
                return false ;
            }
        }
        
        public function createFormateur ($centreId , $data ){
            try{
                $centreDeFormation = $this->centresRepository->find((int) ($centreId));
                if(!$centreDeFormation) return "centre n'existe pas" ;
                $formateur = new Formateurs ;
                $formateur->setCentreDeFormation( $centreDeFormation ) ;
                if(empty($data['nom'])) return "nom obligatoire" ;
                $formateur->setNom($data['nom']);
                if(empty($data['prenom'])) return "prenom obligatoire" ;
                $formateur->setPrenom($data['prenom']);
                if(empty($data['sexe'])) return "sexe obligatoire" ;
                $formateur->setSexe($data['sexe']);
                if(empty($data['numeroTelephone'])) return "numeroTelephone obligatoire" ;
                $formateur->setNumeroTelephone($data['numeroTelephone']);
                $formateur->setEmail($data['email']);
                $formateur->setAdresse($data['adresse']);
                $formateur->setCreatedAt(new DateTimeImmutable);
                $this->entityManager->persist($formateur);
                $this->entityManager->flush();
                return true ;
            }catch(Exception $e){
                return 'Une erreur est survenue.' ;
            }
          
        }

                
        public function updateFormateur ( $data ){
            try{
                $formateur = $this->formateursRepository->find((int) $data['id']);
                if(!$formateur) return "formateur n'existe pas" ;
                if(empty($data['nom'])) return "nom obligatoire" ;
                $formateur->setNom($data['nom']);
                if(empty($data['prenom'])) return "prenom obligatoire" ;
                $formateur->setPrenom($data['prenom']);
                if(empty($data['sexe'])) return "sexe obligatoire" ;
                $formateur->setSexe($data['sexe']);
                if(empty($data['numeroTelephone'])) return "numeroTelephone obligatoire" ;
                $formateur->setNumeroTelephone($data['numeroTelephone']);
                $formateur->setEmail($data['email']);
                $formateur->setAdresse($data['adresse']);
                $formateur->setUpdatedAt(new DateTimeImmutable);
                $this->entityManager->persist($formateur);
                $this->entityManager->flush();
                return true ;
            }catch(Exception $e){
                return 'Une erreur est survenue.' ;
            }
        }

        public function deleteFormateur ($id){
            try{
                $formateur = $this->formateursRepository->find((int) $id ) ;
                $this->entityManager->remove($formateur);
                $this->entityManager->flush();
                return true ;
            }catch(Exception $e){
                return false ;
            }
          
        }




}