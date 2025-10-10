<?php

namespace App\Service\IndemnitesDeFormateuresServices ;

use Exception;
use App\Entity\Abonnement;
use App\Entity\IndemnitesDeFormateures;
use App\Repository\IndemnitesDeFormateuresRepository;

class ObtenirIndemnitesService {
    public function __construct(IndemnitesDeFormateuresRepository $indemnitesRepo){
        $this->indemnitesRepo = $indemnitesRepo ; 
    }

    public function obtenirIndemnitesParAbonnement(Abonnement $abonnementObject){
        try{
            $indemnites = $this->indemnitesRepo->findBy(['Abonnement'=>$abonnementObject]);
            return $indemnites ;
        }catch(Exception $eroor){
            return false ;
        }
    }


}