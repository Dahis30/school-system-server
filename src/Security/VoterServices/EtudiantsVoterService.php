<?php

namespace App\Security\VoterServices ;
use App\Entity\CentresDeFormation;

class EtudiantsVoterService {

    public function canEditEtudiant($userConnected,$etudiant){
        // Ici on va s'assurer que l'étudiant est associé à un centre de formation de l'utilisateur connecté
        $centreDeFormation = $etudiant->getCentreDeFormation() ;
        if(!$centreDeFormation->getUtilisateur()) return false ;
        if ($centreDeFormation->getUtilisateur() !== $userConnected   ) return false ;
        // Si tout se passe bien alors on va retourner true
        return true ;
    }
    
    public function canDeleteEtudiant($userConnected,$etudiant){
        // Ici on va s'assurer que l'étudiant est associé à un centre de formation de l'utilisateur connecté
        $centreDeFormation = $etudiant->getCentreDeFormation() ;
        if(!$centreDeFormation->getUtilisateur()) return false ;
        if ($centreDeFormation->getUtilisateur() !== $userConnected   ) return false ;
        // Si tout se passe bien alors on va retourner true
        return true ;
    }

    public function canGetEtudiants ($userConnected , $centreDeFormation){
        // Ici on va s'assurer que l'utilisateur connecté est celui qui a créé le centre de formation dont il veut obtenir les étudiants associés
        if( !($centreDeFormation instanceof CentresDeFormation)) return false ;
        if ($centreDeFormation->getUtilisateur() !== $userConnected ) return false ;
        return true ;
    }

    public function canCreateEtudiant ($userConnected , $centreDeFormation){
        // Ici on va s'assurer que l'utilisateur connecté est celui qui a créé le centre de formation qu'il veut associer à l'étudiant qu'il veut créer 
        if( !($centreDeFormation instanceof CentresDeFormation)) return false ;
        if ($centreDeFormation->getUtilisateur() !== $userConnected ) return false ;
        return true ;
    }

}