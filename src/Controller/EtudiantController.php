<?php

namespace App\Controller;

use Exception;
use App\Service\EtudiantService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EtudiantController extends AbstractController
{
    public function __construct(EtudiantService $etudiantService , SerializerInterface $serializer){
        $this->etudiantService = $etudiantService ;
        $this->serializer = $serializer ;
    }
    
    #[Route('/etudiants/{centreId}', name: 'app_etudiant_get' , methods: ['GET'])]
    public function getEtudiants($centreId): JsonResponse
    {
        try{
            $etudiants = $this->etudiantService->getEtudiants($centreId);
            if($etudiants === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($etudiants, 'json', ['groups' => 'etudiants'])) ;
            $result = ['etudiants' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    
    /*
        Cette fonction createFormation() permet de créer un etudiant dans la table 'etudiant' et de l'associer à un centre de formation spécifique.
    */
    #[Route('/etudiants/{centreId}', name: 'app_etudiant_create' , methods: ['POST'])]
    public function createEtudiant($centreId ,Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isEtudiantCreated = $this->etudiantService->createEtudiant($centreId , $data  ) ;       
            if($isEtudiantCreated !== true){ return new JsonResponse(['error' => $isEtudiantCreated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "Etudiant bien créée "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }


    /*
        Cette fonction updateEtudiant() permet de modifier un etudiant dans la table 'etudiant' .
    */
    #[Route('/etudiants', name: 'app_etudiant_update' , methods: ['PUT'])]
    public function updateEtudiant(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isEtudiantUpdated = $this->etudiantService->updateEtudiant( $data  ) ;       
            if($isEtudiantUpdated !== true){ return new JsonResponse(['error' => $isEtudiantUpdated],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "etudiant bien modifier "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    
    /*
        Cette fonction deleteEtudiant() permet de supprimer un etudiant depuis la table 'etudiant' .
    */
    #[Route('/etudiants/{id}', name: 'app_etudiant_delete' , methods: ['DELETE'])]
    public function deleteEtudiant($id): JsonResponse
    {
        try{
            $isEtudiantDeleted = $this->etudiantService->deleteEtudiant($id) ;       
            if(!$isEtudiantDeleted){ return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST ); }
            return new JsonResponse(['message' => "etudiant bien supprimer "], 201);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
    





}
