<?php

namespace App\Service\IndemnitesDeFormateuresServices ;

use Exception;
use App\Entity\Abonnement;
use App\Entity\IndemnitesDeFormateures;
use App\Repository\FormateursRepository;
use App\Repository\CentresDeFormationRepository;
use App\Repository\IndemnitesDeFormateuresRepository;

class ObtenirIndemnitesService {
    public function __construct(IndemnitesDeFormateuresRepository $indemnitesRepo , CentresDeFormationRepository $centresDeFormationRepo , FormateursRepository $formateursRepo){
        $this->indemnitesRepo = $indemnitesRepo ; 
        $this->centresDeFormationRepo = $centresDeFormationRepo ; 
        $this->formateursRepo = $formateursRepo ; 
    }

    public function obtenirIndemnitesParAbonnement(Abonnement $abonnementObject){
        try{
            $indemnites = $this->indemnitesRepo->findBy(['Abonnement'=>$abonnementObject]);
            return $indemnites ;
        }catch(Exception $eroor){
            return false ;
        }
    }

    public function obtenirIndemnitesParCentreEtFormateur($centreId , $formateurId = null ){
        try{
            $indemnites = [] ;
            $centreDeformationObject = $this->centresDeFormationRepo->find($centreId) ;
            if(!$centreDeformationObject) return false ;

            if($formateurId){
                $formateurObject = $this->formateursRepo->find($formateurId);
                if(!$formateurObject) return false ;
                $indemnites = $this->indemnitesRepo->findBy(['CentreDeFormation'=>$centreDeformationObject , 'Formateur'=>$formateurObject]);
            }else{
                $indemnites = $this->indemnitesRepo->findBy(['CentreDeFormation'=>$centreDeformationObject]);
            }
            
            return $indemnites ;
        }catch(Exception $eroor){
            return false ;
        }
    }


}