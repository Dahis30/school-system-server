<?php

namespace App\Service\RevenusDuCentreSrvices\AutresRevenus ;

use Exception;
use App\Repository\RevenusDuCentreRepository;
use App\Repository\CentresDeFormationRepository;

class obtenirRevenusService {
    public function __construct( CentresDeFormationRepository $centresDeFormationRepo , RevenusDuCentreRepository $revenusDuCentreRepo ) {
        $this->centresDeFormationRepo = $centresDeFormationRepo ; 
        $this->revenusDuCentreRepo = $revenusDuCentreRepo ; 
    }

    public function obtenirRevenus( $centreId ){
        try{
            $centreObject = $this->centresDeFormationRepo->find($centreId) ;
            if(!$centreObject) return false ;
            $revenus = $this->revenusDuCentreRepo->findBy(['centreDeFormation'=>$centreObject]);
            return $revenus ;
        }catch(Exception $eroor){
            return false ;
        }
    }


}