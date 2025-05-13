<?php

namespace App\Controller;

use App\Service\CentresDeFormationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CentresDeFormationController extends AbstractController
{
    // private $centresDeFormationService ;
    public function __construct(CentresDeFormationService $centresDeFormationService , SerializerInterface $serializer){
        $this->centresDeFormationService = $centresDeFormationService ;
        $this->serializer = $serializer ;
    }

    #[Route('/centres-de-formation', name: 'app_centres_de_formation_get' , methods: ['GET'])]
    public function getCentresDeFormation(): Response
    {
        try{
            $utilisateur = $this->getUser();
            $centresDeformationData = $this->centresDeFormationService->getCentresDeFormation($utilisateur);
            if($centresDeformationData === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($centresDeformationData, 'json', ['groups' => 'centresDeFormation'])) ;
            $result = ['centresDeFormation' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/centres-de-formation', name: 'app_centres_de_formation_create' , methods: ['POST'])]
    public function createCentreDeFormation(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $utilisateur = $this->getUser();
            $isCentreCreated = $this->centresDeFormationService->createCentreDeFormation($data , $utilisateur ) ;       
            if(!$isCentreCreated){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Centre de formation bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/centres-de-formation', name: 'app_centres_de_formation_update' , methods: ['PUT'])]
    public function updateCentreDeFormation(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $utilisateur = $this->getUser();
            $isCentreUpdated = $this->centresDeFormationService->updateCentreDeFormation($data , $utilisateur ) ;       
            if(!$isCentreUpdated){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Centre de formation bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/centres-de-formation/{id}', name: 'app_centres_de_formation_delete' , methods: ['DELETE'])]
    public function deleteCentreDeFormation($id): JsonResponse
    {
        try{
            $utilisateur = $this->getUser();
            $isCentreDeleted = $this->centresDeFormationService->deleteCentreDeFormation($id , $utilisateur ) ;       
            if(!$isCentreDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Centre de formation bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
