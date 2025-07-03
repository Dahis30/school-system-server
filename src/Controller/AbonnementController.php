<?php

namespace App\Controller;

use Exception;
use App\Service\AbonnementService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AbonnementController extends AbstractController
{

    public function __construct(AbonnementService $abonnementService , SerializerInterface $serializer){
        $this->abonnementService = $abonnementService ;
        $this->serializer = $serializer ;
    }
    
    #[Route('/abonnements/{centreId}', name: 'app_abonnement_get' , methods : ['GET'] )]
    public function getAbonnements($centreId): Response
    {
        try{
            $abonnements = $this->abonnementService->getAbonnements($centreId);
            if($abonnements === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($abonnements, 'json', ['groups' => 'abonnements'])) ;
            $result = ['abonnements' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }



        
    /*
        Cette fonction createAbonnement() permet de créer un abonnement dans la table 'abonnement' et de l'associer à un centre de formation spécifique.
    */
    #[Route('/abonnements/{centreId}', name: 'app_abonnement_create' , methods: ['POST'])]
    public function createAbonnement($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isAbonnementCreated = $this->abonnementService->createAbonnement($centreId , $data  ) ;       
            if($isAbonnementCreated !== true){ return new JsonResponse(['error' => $isAbonnementCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Abonnement bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
            // return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }




    

    /*
        Cette fonction updateAbonnement() permet de modifier un abonnement dans la table 'abonnement' .
    */
    #[Route('/abonnements', name: 'app_abonnement_update' , methods: ['PUT'])]
    public function updateAbonnement(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isAbonnementUpdated = $this->abonnementService->updateAbonnement( $data  ) ;       
            if($isAbonnementUpdated !== true){ return new JsonResponse(['error' => $isAbonnementUpdated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Abonnement bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => "Une erreur est survenue."], 500);
        }
    }



    
    
    /*
        Cette fonction deleteAbonnement() permet de supprimer un abonnement depuis la table 'abonnement' .
    */
    #[Route('/abonnements/{id}', name: 'app_abonnement_delete' , methods: ['DELETE'])]
    public function deleteAbonnement($id): JsonResponse
    {
        try{
            $isAbonnementDeleted = $this->abonnementService->deleteAbonnement($id) ;       
            if(!$isAbonnementDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "abonnement bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    





}
