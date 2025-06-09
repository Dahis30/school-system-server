<?php


namespace App\Security\Voter ;

use App\Entity\Etudiant;
use Symfony\Component\Security\Core\Security;
use App\Security\VoterServices\EtudiantsVoterService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EtudiantsVoter extends Voter {

    const EDIT = 'ETUDIANT_EDIT' ;
    const DELETE = 'ETUDIANT_DELETE' ;

    private $security ;

    public function __construct (Security $security ,EtudiantsVoterService $etudiantsVoterService){
        $this->security = $security ;
        $this->etudiantsVoterService = $etudiantsVoterService ;
    }

    protected function supports ( string $attribute , $etudiant) : bool {
        if(!in_array($attribute , [self::EDIT , self::DELETE]) ) return false ;

        if( !($etudiant instanceof Etudiant)) return false ;
        return true ;

    }

    protected function voteOnAttribute (string $attribute , $etudiant , TokenInterface $token) : bool {

        // premierement on vas recuperer l'utilisateur connecte depuis le token
        $user = $token->getUser() ;
        if( !($user instanceof UserInterface) ) return false ;
        
        // Ici on va s'assurer que le role de l'utilisateur connecté est 'ROLE_USER' ;   
        if ( !$this->security->isGranted('ROLE_USER')) return false ;

        switch ($attribute){
            case self::EDIT :
                // on vas verifier si l'utilisateur peut modifier un etudiant
                return $this->etudiantsVoterService->canEditEtudiant($user,$etudiant) ;
                break ;
            case self::DELETE :
                // on vas verifier si l'utilisateur peut supprimer un etudiant
                return $this->etudiantsVoterService->canDeleteEtudiant($user,$etudiant);
                break ;
        }
    }
}