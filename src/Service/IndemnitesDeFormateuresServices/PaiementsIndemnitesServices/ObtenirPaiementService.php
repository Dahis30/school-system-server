<?php

namespace App\Service\IndemnitesDeFormateuresServices\PaiementsIndemnitesServices ;

use Exception;
use App\Repository\IndemnitesDeFormateuresRepository;

class ObtenirPaiementService {
    public function __construct(IndemnitesDeFormateuresRepository $indemnitesRepo  ){
        $this->indemnitesRepo = $indemnitesRepo ;  
    }

    public function obtenirPaiementsParIndemnite($indemniteId){
        try{
            $indemniteObject = $this->indemnitesRepo->find( (int) $indemniteId ) ;
            if(!$indemniteObject) return false ;
            $paiements = $indemniteObject->getPaiementsDesFormateurs() ;
            return $paiements ;
        }catch(Exception $e){
            return false ;
        }
    }


    
}