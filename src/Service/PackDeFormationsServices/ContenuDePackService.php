<?php

namespace App\Service\PackDeFormationsServices ;

use Exception;
use DateTimeImmutable;
use App\Entity\PackFormation;
use App\Entity\ContenuPackFormation;
use App\Repository\FormationRepository;
use App\Repository\FormateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PackFormationRepository;
use App\Repository\ContenuPackFormationRepository;

class ContenuDePackService {
    public function __construct(
        PackFormationRepository $packFormationRepo ,
        EntityManagerInterface $entityManager , 
        FormateursRepository $formateursRepo , 
        FormationRepository $formationRepo ,
        ContenuPackFormationRepository $contenuPackRepo
    )
    {
        $this->packFormationRepo = $packFormationRepo ;
        $this->entityManager = $entityManager ;
        $this->formateursRepo = $formateursRepo ;
        $this->formationRepo = $formationRepo ;
        $this->contenuPackRepo = $contenuPackRepo ;
    }



    
    public function getContentsOfPack ( int $packId){
        try{
            $packDeFormations = $this->packFormationRepo->find((int) $packId);
            if(!$packDeFormations) return false ;
            $contenusDePack = $this->contenuPackRepo->findBy(['PackFormation'=>$packDeFormations]);
            return $contenusDePack ;
        }catch(Exception $eroor){
            return false ;
        }
    }
     

     
    public function creeContenuDePack ( $data ){
        try{
            
            if(empty($data['PackFormation'])) return "Pack de formation est obligatoire" ;
            if(empty($data['Formateur'])) return "Formateur est obligatoire" ;
            if(empty($data['Formation'])) return "Formation est obligatoire" ;
            if( ( (float) $data['pourcentageDeFormateur'] < 0 ) || ( (float) $data['pourcentageDeFormateur'] > 100 )  ) return "Le pourcentage n'est pas valide, il doit être supérieur à 0 et inferieure a 100." ;
            if( ( (float) $data['montantDeFormateur'] < 0 ) ) return "Le montant a payer au formateur n'est pas valide, il doit être supérieur à 0 " ;

            $contenuPackObject = new ContenuPackFormation ;

            $packObject = $this->packFormationRepo->find((int) ($data['PackFormation']['id']));
            if(!$packObject) return "pack de formations n'existe pas" ;

            // Avant de créer un contenu de pack, il faut s'assurer que le total des montants des formateurs pour les contenus existants,
            // plus le montant de ce contenu qu'on veut créer, soit inférieur ou égal au prix global du pack de formation.
            $contenusExist = $this->getContentsOfPack( $packObject->getId() ) ;
            if( $contenusExist === false) return " ereure est survenue l'ors de la recuperaion des contenus exist " ;
            $totalContenuExist = 0 ;
            foreach ( $contenusExist as $contenu){ $totalContenuExist += $contenu->getMontantDeFormateur(); }
            if( ( (float) $data['montantDeFormateur'] + $totalContenuExist ) > $packObject->getPrix() ) return 'Vous avez dépassé le prix du pack de formation, vous ne pouvez donc pas créer ce contenu. ' ;
            /////////////////////////////////////////////////////////

            $contenuPackObject->setPackFormation( $packObject ) ;

            $Formateur = $this->formateursRepo->find( (int) $data['Formateur']['id']) ;
            if(!$Formateur) return "Formateur n'existe pas" ;
            $contenuPackObject->setFormateur( $Formateur ) ;

            $formation = $this->formationRepo->find( (int) $data['Formation']['id'] ) ;
            if(!$formation) return "Formation n'existe pas" ;
            $contenuPackObject->setFormation( $formation ) ;

            $pourcentageDeFormateur = (float) $data['pourcentageDeFormateur'] ;
            $contenuPackObject->setPourcentageDeFormateur( $pourcentageDeFormateur ) ;

            $montantDeFormateur = (float) $data['montantDeFormateur'] ;
            $contenuPackObject->setMontantDeFormateur( $montantDeFormateur ) ;

            $contenuPackObject->setCreatedAt( new DateTimeImmutable ) ;
            
            $this->entityManager->persist($contenuPackObject);
            $this->entityManager->flush();
            
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }
    
           
    public function deleteContenuPack ($id){
        try{
            $contenuPackObject = $this->contenuPackRepo->find((int) $id ) ;
            if(!$contenuPackObject) return false ;
            $this->entityManager->remove($contenuPackObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return false ;
        }
    }

     




}