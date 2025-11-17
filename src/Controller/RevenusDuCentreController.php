<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\RevenusDuCentreSrvices\CreateRevenusService;
use App\Service\RevenusDuCentreSrvices\obtenirRevenusService;
use App\Service\RevenusDuCentreSrvices\ModifierRevenusService;
use App\Service\RevenusDuCentreSrvices\SupprimerRevenusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class RevenusDuCentreController extends AbstractController
{

    
    public function __construct( 
        SerializerInterface $serializer ,
        obtenirRevenusService $obtenirRevenusService ,
        CreateRevenusService $createRevenusService ,
        ModifierRevenusService $modifierRevenusService ,
        SupprimerRevenusService $supprimerRevenusService ,
    ){
        $this->serializer = $serializer ;
        $this->obtenirRevenusService = $obtenirRevenusService ;
        $this->createRevenusService = $createRevenusService ;
        $this->modifierRevenusService = $modifierRevenusService ;
        $this->supprimerRevenusService = $supprimerRevenusService ;
    }
    
    #[Route('/revenus-du-centre', name: 'app_revenus_du_centre' , methods : ['GET'] )]
    public function obtenirRevenusDuCentre(Request $request): Response
    {
        try{
            $centreId = $request->query->get('centreId') ?? null  ;
            $revenus = $this->obtenirRevenusService->obtenirRevenus($centreId);
            if($revenus === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($revenus, 'json', ['groups' => 'revenus-centre'])) ;
            $result = ['revenus' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


    #[Route('/revenus-du-centre', name: 'app_revenus_du_centre_create' , methods : ['POST'] )]
    public function creerRevenusDuCentre(Request $request): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isRevenuCreated = $this->createRevenusService->createRevenus( $data );
            if($isRevenuCreated !== true) return new JsonResponse(['error' => $isRevenuCreated], 500); 
            return new JsonResponse("Le revenu a été enregistré avec succès.", Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }
    

    
    #[Route('/revenus-du-centre', name: 'app_revenus_du_centre_update' , methods : ['PUT'] )]
    public function modifierRevenusDuCentre(Request $request): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isRevenuUpdated = $this->modifierRevenusService->modifierRevenu( $data );
            if($isRevenuUpdated !== true) return new JsonResponse(['error' => $isRevenuUpdated], 500); 
            return new JsonResponse("Le revenu a été enregistré avec succès.", Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


        
    #[Route('/revenus-du-centre', name: 'app_revenus_du_centre_delete' , methods : ['DELETE'] )]
    public function supprimerRevenusDuCentre(Request $request): JsonResponse
    {
        try{
            $revenuId = $request->query->get('revenuId') ?? null  ;
            $isRevenuDeleted = $this->supprimerRevenusService->supprimerRevenu( $revenuId );
            if($isRevenuDeleted !== true) return new JsonResponse(['error' => $isRevenuDeleted], 500); 
            return new JsonResponse("Le revenu a été supprime avec succès.", Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


}
