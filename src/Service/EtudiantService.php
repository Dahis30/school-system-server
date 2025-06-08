<?php

namespace App\Service ;

use DateTimeImmutable;
use App\Entity\Etudiant;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;

class EtudiantService {

    public function __construct(EtudiantRepository $etudiantRepository, CentresDeFormationRepository $centresRepository , EntityManagerInterface $entityManager ){
        $this->etudiantRepository = $etudiantRepository ;
        $this->centresRepository = $centresRepository ;  
        $this->entityManager = $entityManager ;
    }

    public function getEtudiants($centreId){
        try{
            $centreDeFormation = $this->centresRepository->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $etudiants = $this->etudiantRepository->findBy(['CentreDeFormation'=>$centreDeFormation]);
            return  $etudiants ;
        }catch(Exception $e){
            return false ;
        }
    }

    public function createEtudiant ($centreId , $data ){
        try{
            $centreDeFormation = $this->centresRepository->find((int) ($centreId));
            if(!$centreDeFormation) return "centre n'existe pas" ;
            $etudiantObject = new Etudiant ;
            $etudiantObject->setCentreDeFormation( $centreDeFormation ) ;
            if(empty($data['nomComplet'])) return "Nom complet obligatoire" ;
            $etudiantObject->setNomComplet((string) $data['nomComplet']);
            $etudiantObject->setNumeroTelephone((string) $data['numeroTelephone']);
            $etudiantObject->setNomTuteur((string) $data['nomTuteur']);
            $etudiantObject->setNumeroTelephoneTuteur((string) $data['numeroTelephoneTuteur']);
            $etudiantObject->setNiveauScolaire((string) $data['niveauScolaire']);
            $etudiantObject->setGroupe((string) $data['groupe']);
            $etudiantObject->setAdresse((string) $data['adresse']);
            $etudiantObject->setStatut(true);
            $etudiantObject->setCreatedAt(new DateTimeImmutable);
            $this->entityManager->persist($etudiantObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
      
    }

    
                
    public function updateEtudiant ( $data ){
        try{
            $etudiantObject = $this->etudiantRepository->find((int) $data['id']);
            if(!$etudiantObject) return "etudiant n'existe pas" ;
            if(empty($data['nomComplet'])) return "Nom complet obligatoire" ;
            $etudiantObject->setNomComplet((string) $data['nomComplet']);
            $etudiantObject->setNumeroTelephone((string) $data['numeroTelephone']);
            $etudiantObject->setNomTuteur((string) $data['nomTuteur']);
            $etudiantObject->setNumeroTelephoneTuteur((string) $data['numeroTelephoneTuteur']);
            $etudiantObject->setNiveauScolaire((string) $data['niveauScolaire']);
            $etudiantObject->setGroupe((string) $data['groupe']);
            $etudiantObject->setAdresse((string) $data['adresse']);
            $etudiantObject->setUpdatedAt(new DateTimeImmutable);
            $this->entityManager->persist($etudiantObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return 'Une erreur est survenue.' ;
        }
    }


    
    public function deleteEtudiant ($id){
        try{
            $etudiantObject = $this->etudiantRepository->find((int) $id ) ;
            if(!$etudiantObject) return false ;
            $this->entityManager->remove($etudiantObject);
            $this->entityManager->flush();
            return true ;
        }catch(Exception $e){
            return false ;
        }
      
    }




}