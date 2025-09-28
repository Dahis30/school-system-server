<?php

namespace App\Controller;

use Exception;
use App\Service\AbonnementServices\AbonnementNormalService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\AbonnementServices\AbonnementPackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AbonnementController extends AbstractController
{

    public function __construct(AbonnementNormalService $abonnementNormalService , AbonnementPackService $abonnementPackService , SerializerInterface $serializer){
        $this->abonnementNormalService = $abonnementNormalService ;
        $this->abonnementPackService = $abonnementPackService ;
        $this->serializer = $serializer ;
    }
    
    #[Route('/abonnements/{centreId}', name: 'app_abonnement_get' , methods : ['GET'] )]
    public function getAbonnements($centreId): Response
    {
        try{
            $abonnements = $this->abonnementNormalService->getAbonnements($centreId);
            if($abonnements === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($abonnements, 'json', ['groups' => 'abonnements'])) ;
            $result = ['abonnements' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }



        
    /*
        Cette fonction createAbonnementNormal() permet de créer un abonnement dans la table 'abonnement' et de l'associer à un centre de formation spécifique.
    */
    #[Route('/abonnements/{centreId}', name: 'app_abonnement_create' , methods: ['POST'])]
    public function createAbonnementNormal($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isAbonnementCreated = $this->abonnementNormalService->createAbonnementNormal($centreId , $data  ) ;       
            if($isAbonnementCreated !== true){ return new JsonResponse(['error' => $isAbonnementCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Abonnement bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
            // return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }




    

    /*
        Cette fonction updateAbonnementNormal() permet de modifier un abonnement dans la table 'abonnement' .
    */
    #[Route('/abonnements', name: 'app_abonnement_update' , methods: ['PUT'])]
    public function updateAbonnementNormal(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isAbonnementUpdated = $this->abonnementNormalService->updateAbonnementNormal( $data  ) ;       
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
            $isAbonnementDeleted = $this->abonnementNormalService->deleteAbonnement($id) ;       
            if($isAbonnementDeleted !== true){ return new JsonResponse(['error' => $isAbonnementDeleted ],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "abonnement bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

        
    #[Route('/abonnement/pack/{centreId}', name: 'app_abonnement_get_pack' , methods : ['GET'] )]
    public function getAbonnementsDepack($centreId): Response
    {
        try{
            $abonnements = $this->abonnementPackService->getAbonnementsPack($centreId);
            if($abonnements === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($abonnements, 'json', ['groups' => 'abonnementsDePack'])) ;
            $result = ['abonnements' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }



    #[Route('/abonnement/contenu/{abonnementId}', name: 'app_abonnement_get_contenu' , methods : ['GET'] )]
    public function getConteuAbonnement($abonnementId): Response
    {
        try{
            $contenus = $this->abonnementPackService->getConteuAbonnement($abonnementId);
            if($contenus === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($contenus, 'json', ['groups' => 'contenu_abonnement'])) ;
            $result = ['contenuAbonnement' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


    /*
       Cette fonction createAbonnementDePack() permet de créer un abonnement de pack dans la table 'abonnement' et de l'associer à un centre de formation spécifique et un pack de formation spécifique .
    */
    #[Route('/abonnement/pack/{centreId}', name: 'app_abonnement_pack_create' , methods: ['POST'])]
    public function createAbonnementDePack($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isAbonnementCreated = $this->abonnementPackService->createAbonnementDePack($centreId , $data  ) ;       
            if($isAbonnementCreated !== true){ return new JsonResponse(['error' => $isAbonnementCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Abonnement bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
            // return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    
    /*
        Cette fonction updateAbonnementNormal() permet de modifier un abonnement dans la table 'abonnement' .
    */
    #[Route('/abonnement/pack', name: 'app_abonnement_update' , methods: ['PUT'])]
    public function updateAbonnementDePack(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isAbonnementUpdated = $this->abonnementPackService->updateAbonnementDePack( $data  ) ;       
            if($isAbonnementUpdated !== true){ return new JsonResponse(['error' => $isAbonnementUpdated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Abonnement bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => "Une erreur est survenue."], 500);
        }
    }






}
