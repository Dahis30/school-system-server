<?php

namespace App\Service ;

use DateTimeImmutable;
use App\Entity\Formateurs;
use App\Entity\FormateursFormation;
use App\Repository\FormationRepository;
use App\Repository\FormateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;
use App\Repository\FormateursFormationRepository;


class FormateursService {
        public function __construct(EntityManagerInterface $entityManager , FormateursRepository $formateursRepository , CentresDeFormationRepository $centresRepository , FormationRepository $formationRepo , FormateursFormationRepository $formateursFormationRepository){
            $this->entityManager = $entityManager ;
            $this->formateursRepository = $formateursRepository ; 
            $this->centresRepository = $centresRepository ;  
            $this->formationRepo = $formationRepo ;
            $this->formateursFormationRepository = $formateursFormationRepository ;
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


        public function getCurrentRelationFormationsFormateur ($formateurId){

            try{
                $formateur = $this->formateursRepository->find((int) $formateurId ) ;
                if(!$formateur) return false ;
                $relations = $formateur->getFormateursFormations() ;
                return  $relations ;
            }catch(Exception $e){
                return false ;
            }

        }




        public function getCurrentFormations ($formateurId){

            try{
                $formateur = $this->formateursRepository->find((int) $formateurId ) ;
                if(!$formateur) return false ;
                $formations = [] ;
                $relations = $formateur->getFormateursFormations() ;
                foreach($relations as $item){
                    if($item->getFormation()) $formations [] = $item->getFormation() ;
                }
                return  $formations ;
            }catch(Exception $e){
                return false ;
            }

        }

        public function getFormationsDisponibles ( $centreId , $formateurId){

            try{
                $centreDeFormation = $this->centresRepository->find((int) ($centreId));
                if(!$centreDeFormation) return false ;
                $formateur = $this->formateursRepository->find((int) $formateurId ) ;
                if(!$formateur) return false ;
                $formations = $this->formationRepo->findBy(['CentreDeFormation'=>$centreDeFormation]);
                $formationsToExeclude = $this->getCurrentFormations($formateur->getId()) ;
                $formationsData = [] ;
                foreach($formations as $formation){
                    if( !( in_array( $formation , $formationsToExeclude) )) $formationsData[] = $formation ;         
                }
                return  $formationsData ;
            }catch(Exception $e){
                return false ;
            }

        }

        
        
        public function associerFormationAuFormateur ($formateurId , $data ){
            try{
                
                $formateur = $this->formateursRepository->find((int) $formateurId ) ;
                if(!$formateur) return "formateur n'axiste pas" ;

                $centreDeFormation = $formateur->getCentreDeFormation();
                if(!$centreDeFormation) return "centre n'existe pas" ;

                $formation = $this->formationRepo->find((int) $data["formationObject"]["id"]);
                if(!$formation) return "formation n'existe pas" ;
                if($formation->getCentreDeFormation() !== $formateur->getCentreDeFormation() ) return " la formation doit etre de le meme centre de formation associer au formateur" ;

                $relationsAlreadyExist = $formateur->getFormateursFormations() ;
                foreach($relationsAlreadyExist as $item){
                    if($item->getFormation() == $formation ) return "cette formation est deja associer au formateur" ;
                }


                $relation = new FormateursFormation ;
                $relation->setFormateurs($formateur);
                $relation->setFormation($formation);
                $typeDePaiement = $data["typeDePaiement"] ;
                $relation->setTypeDePaiement($typeDePaiement);

                if($typeDePaiement == "pourcentage"){
                    if((float) $data["pourcentage"] > 100 || (float) $data["pourcentage"] < 0 ) return "Le pourcentage doit être compris entre 0 et 100.";
                    $relation->setPourcentage( (float) $data["pourcentage"]); 
                }
                elseif($typeDePaiement == "montant fixe"){
                     $relation->setMontant( (float) $data["montant"]);
                }
                else{ return "type de paiement pas valide" ; }

                $this->entityManager->persist($relation);
                $this->entityManager->flush();
                return true ;
            }catch(Exception $e){
                return 'Une erreur est survenue.' ;
            }
    
        }

        
        public function retirerFormationAuFormateur ($relationFormationId){
            try{
                $relation = $this->formateursFormationRepository->find((int) $relationFormationId ) ;
                $this->entityManager->remove($relation);
                $this->entityManager->flush();
                return true ;
            }catch(Exception $e){
                return false ;
            }
        }





}