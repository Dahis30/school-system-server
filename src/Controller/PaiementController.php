<?php

namespace App\Controller;

use Exception;
use App\Service\PaiementService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PaiementController extends AbstractController
{

    
    public function __construct(PaiementService $paiementService , SerializerInterface $serializer){
        $this->paiementService = $paiementService ;
        $this->serializer = $serializer ;
    }
    
    


    
    #[Route('/paiements/{centreId}', name: 'app_paiement_get' , methods : ['GET'] )]
    public function getPaiementsByCentre($centreId): Response
    {
        try{
            $paiements = $this->paiementService->getPaiementsByCentre($centreId);
            if($paiements === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($paiements, 'json', ['groups' => 'paiements'])) ;
            $result = ['paiements' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


    #[Route('/paiements/{abonnementId}/abonnement', name: 'app_paiement_get_by_abonnement' , methods : ['GET'] )]
    public function getPaiementsByAbonnement($abonnementId): Response
    {
        try{
            $paiements = $this->paiementService->getPaiementsByAbonnement($abonnementId);
            if($paiements === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($paiements, 'json', ['groups' => 'paiements'])) ;
            $result = ['paiements' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }




    
        
    /*
        Cette fonction createPaiement() permet de créer un paiement dans la table 'paiement' pour un abonnement spécifique et de l'associer à un centre de formation spécifique.
    */
    #[Route('/paiements', name: 'app_paiement_create' , methods: ['POST'])]
    public function createPaiement(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isPaiementCreated = $this->paiementService->createPaiement( $data  ) ;       
            if($isPaiementCreated !== true){ return new JsonResponse(['error' => $isPaiementCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Paiement bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }




    /*
        Cette fonction deletePaiement() permet de supprimer un paiement depuis la table 'paiement' .
    */
    #[Route('/paiements/{id}', name: 'app_paiement_delete' , methods: ['DELETE'])]
    public function deletePaiement($id): JsonResponse
    {
        try{
            $isPaiementDeleted = $this->paiementService->deletePaiement($id) ;       
            if(!$isPaiementDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "paiement bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    







}
