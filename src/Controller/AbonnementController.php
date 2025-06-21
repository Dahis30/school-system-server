<?php

namespace App\Controller;

use Exception;
use App\Service\AbonnementService;
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
}
