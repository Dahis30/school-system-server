<?php

namespace App\Security\VoterServices ;

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

}