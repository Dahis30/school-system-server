<?php

namespace App\Service ;

use App\Entity\Users;
use App\Entity\DemandeInscription;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use App\Repository\DemandeInscriptionRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DemandeInscriptionService {

    private $DemandeInscriptionRepository ;
    private $security ;
    private $entityManager ;
    private $passwordEncoder ;
    private $roleRepository ;
    public function __construct(DemandeInscriptionRepository $DemandeInscriptionRepository , Security $security , EntityManagerInterface $entityManager ,  UserPasswordHasherInterface  $passwordEncoder , RoleRepository $roleRepository){
        $this->DemandeInscriptionRepository = $DemandeInscriptionRepository ;
        $this->security = $security ;
        $this->entityManager = $entityManager ;
        $this->passwordEncoder = $passwordEncoder ;
        $this->roleRepository = $roleRepository ;
    }

    public function getDemandesInscription(){
        $data = $this->DemandeInscriptionRepository->findAll();
        $demandes = [] ;
        foreach($data as $demande){
            if(!$demande->isValidated())  $demandes [] = $demande ;
        }
        return  $demandes ;
    }


    public function createDemande($data){
        $demande = new DemandeInscription ;
        // TODO : Il faut que j'utilise une fonction comme htmlspecialchars avant de stocker les données dans la base de données.
        $demande->setNom($data['nom']);
        $demande->setPrenom($data['prenom']);
        $demande->setEmail($data['email']);
        $demande->setNumeroTelephone($data['numeroTelephone']);
        $demande->setMotDePass($data['motDePass']);
        $demande->setSexe($data['sexe']);
        $this->entityManager->persist($demande);
        $this->entityManager->flush(); 
        return true ;
    }

    
    public function validateDemandes($data){


        $role = $this->roleRepository->findOneBy(['titre'=>'ROLE_USER']);
        if(!$role) return false ;

        foreach( $data as $demande){
            $demandeObject =  $this->DemandeInscriptionRepository->find($demande['id']);
            if(!$demandeObject) return false ;
            $user = new Users ; 
            $demandeObject->setValidated(true);
            $user->setDemandeInscription($demandeObject);
            $user->setNom($demandeObject->getNom());
            $user->setPrenom($demandeObject->getPrenom());
            $user->setSexe($demandeObject->getSexe());
            $user->setNumeroTelephone($demandeObject->getNumeroTelephone());
            $user->setEmail($demandeObject->getEmail());
            $user->setPassword($this->passwordEncoder->hashPassword($user, $demandeObject->getMotDePass()));
            $user->setRoles($role);
            
            $this->entityManager->persist($user);
            $this->entityManager->persist($demandeObject);
        }
        $this->entityManager->flush();
        return true ;
    }

    public function deleteDemandes($data){

        foreach( $data as $demande){
            $demandeObject =  $this->DemandeInscriptionRepository->find($demande['id']);
            if(!$demandeObject) return false ;
            $this->entityManager->remove($demandeObject);
        }
        $this->entityManager->flush();
        return true ;

    }

}