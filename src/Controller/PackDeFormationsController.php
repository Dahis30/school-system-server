<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\PackDeFormationsServices\PackDeFormationsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PackDeFormationsController extends AbstractController
{
    public function __construct(PackDeFormationsService $packDeFormationsService , SerializerInterface $serializer){
        $this->packDeFormationsService = $packDeFormationsService ;
        $this->serializer = $serializer ;
    }

    #[Route('/pack/{centreId}', name: 'app_pack_de_formations' , methods : ['GET'] )]
    public function getPacksByCentre($centreId): JsonResponse
    {
        try{
            $packsDeFormations = $this->packDeFormationsService->getPacksByCentre($centreId);
            if($packsDeFormations === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($packsDeFormations, 'json', ['groups' => 'packsDeFormations'])) ;
            $result = ['packsDeFormations' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }
    
        
    /*
        Cette fonction createPack() permet de créer un pack dans la table 'PackFormation' et de l'associer à un centre de formation spécifique.
    */
    #[Route('/pack/{centreId}', name: 'app_pack_de_formations_create' , methods: ['POST'])]
    public function createPack($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isPackCreated = $this->packDeFormationsService->createPack($centreId , $data  ) ;       
            if($isPackCreated !== true){ return new JsonResponse(['error' => $isPackCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Pack est bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }
    
    /*
        Cette fonction updatePack() permet de modifier un pack de formations dans la table 'PackFormation' .
    */
    #[Route('/pack', name: 'app_pack_de_formations_update' , methods: ['PUT'])]
    public function updatePack(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isPackUpdated = $this->packDeFormationsService->updatePack( $data  ) ;       
            if($isPackUpdated !== true){ return new JsonResponse(['error' => $isPackUpdated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Pack de formations est bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => "Une erreur est survenue."], 500);
        }
    }



      
    
    /*
        Cette fonction deletePck() permet de supprimer un pack de formations depuis la table 'PackFormation' .
    */
    #[Route('/pack/{id}', name: 'app_pack_de_formations_delete' , methods: ['DELETE'])]
    public function deletePck($id): JsonResponse
    {
        try{
            $isPackDeleted = $this->packDeFormationsService->deletePck($id) ;       
            if(!$isPackDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Pack de formations est bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    






}
