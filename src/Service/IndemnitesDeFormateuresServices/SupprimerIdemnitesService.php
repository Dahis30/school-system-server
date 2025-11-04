<?php

namespace App\Service\IndemnitesDeFormateuresServices ;

use Exception;
use Doctrine\ORM\EntityManagerInterface;

class SupprimerIdemnitesService {
    public function __construct(EntityManagerInterface $entityManager ){
        $this->entityManager = $entityManager ;  
    }

    public function SupprimerIdemnites($indemnites){
        try{
            foreach( $indemnites as $indemnite){
                $this->entityManager->remove($indemnite);
            }
            $this->entityManager->flush();
            return true ;
        }catch(Exception $eroor){
            return false ;
        }
    }


}