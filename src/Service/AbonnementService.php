<?php

namespace App\Service ;

use Exception;
use App\Repository\AbonnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentresDeFormationRepository;


class AbonnementService{

    public function __construct(AbonnementRepository $abonnementRepo , EntityManagerInterface $entityManager , CentresDeFormationRepository $centresRepository , ){
        $this->abonnementRepo = $abonnementRepo ;
        $this->entityManager = $entityManager ;
        $this->centresRepository = $centresRepository ;
    }

    public function getAbonnements($centreId){
        try{
            $centreDeFormation = $this->centresRepository->find((int) $centreId);
            if(!$centreDeFormation) return false ;
            $abonemments = $this->abonnementRepo->findBy(['CentresDeFormation'=>$centreDeFormation]);
            return $abonemments ;
        }catch(Exception $eroor){
            return false ;

        }
    }


}