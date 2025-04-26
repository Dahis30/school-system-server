<?php

namespace App\Controller;

use Exception;
use App\Entity\DemandeInscription;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\DemandeInscriptionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class DemandeInscriptionController extends AbstractController
{
    private $entityManager ;
    private $passwordEncoder ;
    private $demandeInscriptionService ;
    private $serializer ;
    public function __construct(EntityManagerInterface $entityManager ,  UserPasswordHasherInterface  $passwordEncoder, DemandeInscriptionService $demandeInscriptionService , SerializerInterface $serializer ,){
        $this->entityManager = $entityManager ;
        $this->passwordEncoder = $passwordEncoder ;
        $this->demandeInscriptionService = $demandeInscriptionService ;
        $this->serializer = $serializer ;

    }


        
    #[Route('/demande/inscription', name: 'app_get_demande_inscription' , methods: ['GET'])]
    public function getDemandesInscripion(): JsonResponse
    {
        try{
            $demandesData = $this->demandeInscriptionService->getDemandesInscription() ;
            $demandeJson = json_decode($this->serializer->serialize($demandesData, 'json', ['groups' => 'demandesInscription'])) ;
            $result = ['demandes' => $demandeJson ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }
        catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }


    
    #[Route('/demande/inscription', name: 'app_demande_inscription_create' , methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isDemandesCreated = $this->demandeInscriptionService->createDemande($data) ;
            if(!$isDemandesCreated){
                return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            }
            return new JsonResponse(['message' => "Demande d'inscription bien créée "], 201);
        }
        catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }

    #[Route('/demande/inscription/validate', name: 'app_demande_inscription_validate' , methods: ['POST'] )]
    public function validate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $isDemandesValidated = $this->demandeInscriptionService->validateDemandes($data) ;
        if(!$isDemandesValidated){
             return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
        }
        return new JsonResponse(['message' => 'Les demandes que tu as sélectionnées étaient bien valides.'], 201);
    }

    #[Route('/demande/inscription/delete', name: 'app_demande_inscription_delete' , methods: ['POST'] )]
    public function delete(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $isDemandesDeleted = $this->demandeInscriptionService->deleteDemandes($data) ;
        if(!$isDemandesDeleted){
            return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
        }
        return new JsonResponse(['message' => 'Les demandes que tu as sélectionnées ont bien été supprimées.'], 201);
    }


}
