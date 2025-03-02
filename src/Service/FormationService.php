<?php

namespace App\Service ;

use App\Repository\FormationRepository;
use Symfony\Component\Security\Core\Security;

class FormationService {

    private $formationRepo ;
    private $security ;
    public function __construct(FormationRepository $formationRepo , Security $security){
        $this->formationRepo = $formationRepo ;
        $this->security = $security ;
    }

    public function getFormationsByUser($user){
        $formations = $this->formationRepo->findBy(['utilisateur'=>$user]);
        return  $formations ;

    }

}