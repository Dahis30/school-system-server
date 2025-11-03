<?php

namespace App\Service\IndemnitesDeFormateuresServices\PaiementsIndemnitesServices ;

use DateTime;
use Exception;
use DateTimeImmutable;
use App\Entity\PaiementsDesFormateurs;
use App\Entity\IndemnitesDeFormateures;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IndemnitesDeFormateuresRepository;

class CreatePaiementService {
    public function __construct(EntityManagerInterface $entityManager , IndemnitesDeFormateuresRepository $indemnitesRepo , ){
        $this->entityManager = $entityManager ;  
        $this->indemnitesRepo = $indemnitesRepo ;  
    }

    public function createPayementForIndemnite($data){

        try{
            $indemniteeObject = $this->indemnitesRepo->find($data['indemniteObject']['id']) ;
            if(!$indemniteeObject) return "L'indemnité que vous avez sélectionnée n'existe pas." ;
            
            $resteApayer = $indemniteeObject->getMontantDeFormateur() - $indemniteeObject->getMontantPayee(); 
            if( (float) $data['montant'] > $resteApayer ) return "Le montant du paiement est supérieur au reste à payer." ;
            
            $paiementObject = new PaiementsDesFormateurs ;
            $paiementObject->setMontant((float)$data['montant']) ;
            $paiementObject->setModePaiement($data['modePaiement']) ;
            $datePaiement = new DateTimeImmutable($data['datePaiement']);
            $paiementObject->setDatePaiement($datePaiement) ;
            $paiementObject->setCommentaire($data['commentaire']) ;
            $paiementObject->setIndemniteDeFormateur($indemniteeObject) ;
            $paiementObject->setCreatedAt( new DateTimeImmutable ) ;

            $indemniteeObject->setMontantPayee( $indemniteeObject->getMontantPayee() +  $paiementObject->getMontant() ) ;

            $this->entityManager->persist($paiementObject);
            $this->entityManager->persist($indemniteeObject);
            $this->entityManager->flush();
            return true ;

        }catch(Exception $e){
            return "Une erreur est survenue." ;
        }
    }


    
}