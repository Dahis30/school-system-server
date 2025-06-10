<?php


namespace App\Security\Voter ;

use App\Entity\Etudiant;
use App\Entity\CentresDeFormation;
use Symfony\Component\Security\Core\Security;
use App\Security\VoterServices\EtudiantsVoterService;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EtudiantsVoter extends Voter {

    const GET = 'ETUDIANT_GET' ;
    const CREATE = 'ETUDIANT_CREATE';
    const EDIT = 'ETUDIANT_EDIT' ;
    const DELETE = 'ETUDIANT_DELETE' ;

    private $security ;

    public function __construct (Security $security ,EtudiantsVoterService $etudiantsVoterService){
        $this->security = $security ;
        $this->etudiantsVoterService = $etudiantsVoterService ;
    }

    protected function supports ( string $attribute , $object ) : bool {
        if(!in_array($attribute , [self::EDIT , self::DELETE , self::GET , self::CREATE]) ) return false ;
        if( !($object instanceof Etudiant) && !($object instanceof CentresDeFormation)) return false ;
        return true ;
    }

    protected function voteOnAttribute (string $attribute , $object , TokenInterface $token) : bool {

        // premierement on vas recuperer l'utilisateur connecte depuis le token
        $user = $token->getUser() ;
        if( !($user instanceof UserInterface) ) return false ;
        
        // Ici on va s'assurer que le role de l'utilisateur connecté est 'ROLE_USER' ;   
        if ( !$this->security->isGranted('ROLE_USER')) return false ;

        switch ($attribute){
            case self::EDIT :
                // on vas verifier si l'utilisateur peut modifier un etudiant
                return $this->etudiantsVoterService->canEditEtudiant($user,$object) ;
                break ;
            case self::DELETE :
                // on vas verifier si l'utilisateur peut supprimer un etudiant
                return $this->etudiantsVoterService->canDeleteEtudiant($user,$object);
                break ;
            case self::GET:
                return $this->etudiantsVoterService->canGetEtudiants($user,$object ) ;
                break ;
            case self::CREATE:
                return $this->etudiantsVoterService->canCreateEtudiant($user,$object ) ;
                break ;
        }
    }
}