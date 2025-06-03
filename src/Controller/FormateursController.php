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


    /*
    Cette API permet de fournir les formations déjà associées à un formateur spécifique, sous forme de JSON.
    */
    #[Route('/formateur/{formateurId}/getCurrentFormations', name: 'app_formateurs_getCurrentFormations' , methods: ['GET'])]
    public function getCurrentFormations($formateurId): JsonResponse
    {
        try{
            $formationsData = $this->formateursService->getCurrentRelationFormationsFormateur($formateurId);
            if($formationsData === false)  return new JsonResponse(['error' => 'Une erreur est survenue lors de la récupération des formations de ce formateur.'],  JsonResponse::HTTP_BAD_REQUEST );    
            $json = json_decode($this->serializer->serialize($formationsData, 'json', ['groups' => 'relationFormationFormateur'])) ;
            $result = ['formations' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    
    /*
    Cette API permet de fournir les formations qui possible d'associer a un formateur spécifique, sous forme de JSON.
    */
    #[Route('/formateur/{formateurId}/getFormationsDisponibles/{centreId}', name: 'app_formateurs_get_formations' , methods: ['GET'])]
    public function getFormationsDisponibles( $formateurId , $centreId): JsonResponse
    {
        try{
            $formationsData = $this->formateursService->getFormationsDisponibles($centreId ,$formateurId);
            if($formationsData === false)  return new JsonResponse(['error' => "Une erreur est survenue lors de la récupération des formations de ce centre de formation."],  JsonResponse::HTTP_BAD_REQUEST );    
            $json = json_decode($this->serializer->serialize($formationsData, 'json', ['groups' => 'formation'])) ;
            $result = ['formations' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

        
    /*
    Cette API permet d'associer un formateur à une formation spécifique, avec d'autres données et sous certaines conditions.
    */
    #[Route('/formateur/{formateurId}/associerFormationAuFormateur', name: 'app_formateurs_associerFormationAuFormateur' , methods: ['POST'])]
    public function associerFormationAuFormateur( $formateurId  , Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isFormationCreated = $this->formateursService->associerFormationAuFormateur($formateurId , $data  ) ;       
            if($isFormationCreated !== true){ return new JsonResponse(['error' => $isFormationCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "formation ete bien associer au formateur "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    
    /*
       Cette API permet de retirer une formation d’un formateur si elle est déjà associée à celui-ci.
    */
    #[Route('/formateur/{relationFormationId}/retirerFormationAuFormateur', name: 'app_formateurs_retirerFormationAuFormateur' , methods: ['DELETE'])]
    public function retirerFormationAuFormateur( $relationFormationId  , Request $request): JsonResponse
    {
        try{
            $isFormationDeleted = $this->formateursService->retirerFormationAuFormateur($relationFormationId) ;       
            if(!$isFormationDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Formation bien supprimer de le formateur "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    
    



}
