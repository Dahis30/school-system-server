<?php

namespace App\Controller;

use Exception;
use App\Service\FormateursService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormateursController extends AbstractController
{

    private $formateursService ;
    private $serializer ;
    public function __construct(FormateursService $formateursService, SerializerInterface $serializer){
        $this->formateursService = $formateursService ;
        $this->serializer = $serializer ;
    }

    #[Route('/formateurs/{centreId}', name: 'app_formateurs_get' , methods: ['GET'])]
    public function getformateurs($centreId): JsonResponse
    {
        try{
         
            $formateurs = $this->formateursService->getformateurs($centreId);
            if($formateurs === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($formateurs, 'json', ['groups' => 'formateurs'])) ;
            $result = ['formateurs' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    
    #[Route('/formateurs/{centreId}', name: 'app_formateurs_create' , methods: ['POST'])]
    public function createFormateur($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isFormateurCreated = $this->formateursService->createFormateur($centreId , $data  ) ;       
            if($isFormateurCreated !== true){ return new JsonResponse(['error' => $isFormateurCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Centre de formation bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    #[Route('/formateurs', name: 'app_formateurs_update' , methods: ['PUT'])]
    public function updateFormateur(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isFormateurCreated = $this->formateursService->updateFormateur( $data  ) ;       
            if($isFormateurCreated !== true){ return new JsonResponse(['error' => $isFormateurCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Centre de formation bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    
    #[Route('/formateurs/{id}', name: 'app_formateurs_delete' , methods: ['DELETE'])]
    public function deleteFormateur($id): JsonResponse
    {
        try{
            $isFormateurDeleted = $this->formateursService->deleteFormateur($id) ;       
            if(!$isFormateurDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Formateur bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    



}
