<?php

namespace App\Controller;

use Exception;
use App\Entity\Formation;
use App\Service\FormationService;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    private $formationService ;
    private $serializer ;
    private $entityManager ;
    public function __construct(FormationService $formationService, SerializerInterface $serializer , EntityManagerInterface $entityManager){
        $this->formationService = $formationService ;
        $this->serializer = $serializer ;
        $this->entityManager = $entityManager ;
    }

    /*
        Cette fonction getFormations() permet d'obtenir toutes les formations par id d'un centre de formation donné en paramètre.
    */
    #[Route('/formation/{centreId}', name: 'app_formation' , methods: ['GET'])]
    public function getFormations($centreId): JsonResponse
    {
        try{
            $formationsData = $this->formationService->getFormationsByCentre($centreId) ;
            if($formationsData === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($formationsData, 'json', ['groups' => 'formation'])) ;
            $result = ['formations' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    /*
        Cette fonction createFormation() permet de créer une formation dans la table des formations et de l'associer à un centre de formation spécifique.
    */
    #[Route('/formation/{centreId}', name: 'app_formation_create' , methods: ['POST'])]
    public function createFormation($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isFormationCreated = $this->formationService->createFormation($centreId , $data  ) ;       
            if($isFormationCreated !== true){ return new JsonResponse(['error' => $isFormationCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "formation bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }



    

    #[Route('/formation', name: 'app_formation_update' , methods: ['PUT'])]
    public function updateFormation(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isFormationUpdated = $this->formationService->updateFormation( $data  ) ;       
            if($isFormationUpdated !== true){ return new JsonResponse(['error' => $isFormationUpdated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "formation bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    
    
    #[Route('/formation/{id}', name: 'app_formation_delete' , methods: ['DELETE'])]
    public function deleteFormation($id): JsonResponse
    {
        try{
            $isFormationDeleted = $this->formationService->deleteFormation($id) ;       
            if(!$isFormationDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Formation bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    


}
