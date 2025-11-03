<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\IndemnitesDeFormateuresServices\ObtenirIndemnitesService;
use App\Service\IndemnitesDeFormateuresServices\PaiementsIndemnitesServices\CreatePaiementService;
use App\Service\IndemnitesDeFormateuresServices\PaiementsIndemnitesServices\ObtenirPaiementService;
use App\Service\IndemnitesDeFormateuresServices\PaiementsIndemnitesServices\SupprimerPaiementService;

final class IndemnitesDeFormateuresController extends AbstractController
{

    
    public function __construct( SerializerInterface $serializer , ObtenirIndemnitesService $obtenirIndemnitesService , CreatePaiementService $createPaiementService , ObtenirPaiementService $obtenirPaiementService , SupprimerPaiementService $supprimerPaiementService){

        $this->serializer = $serializer ;
        $this->obtenirIndemnitesService = $obtenirIndemnitesService ;
        $this->createPaiementService = $createPaiementService ;
        $this->obtenirPaiementService = $obtenirPaiementService ;
        $this->supprimerPaiementService = $supprimerPaiementService ;

    }
    
    #[Route('/indemnites-formateurs', name: 'app_formateurs_indemnites_get' , methods : ['GET'] )]
    public function obtenirIndemnitesDeFormateurs(Request $request): Response
    {
        try{
            $centreId = $request->query->get('centreId') ?? null  ;
            $formateurId = $request->query->get('formateurId') ?? null  ;
            $indemnites = $this->obtenirIndemnitesService->obtenirIndemnitesParCentreEtFormateur($centreId , $formateurId);
            if($indemnites === false)  return new JsonResponse(['error' => 'Une erreur est survenue.'],  JsonResponse::HTTP_BAD_REQUEST );
            $json = json_decode($this->serializer->serialize($indemnites, 'json', ['groups' => 'indemnites-formateures'])) ;
            $result = ['indemnites' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);

        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


    #[Route('/indemnites-formateurs-paiement', name: 'app_formateurs_indemnites_paiement_create' , methods : ['POST'] )]
    public function createPayement(Request $request): Response
    {
        try{
            $data = json_decode($request->getContent(), true);
            $isPaiementCreated = $this->createPaiementService->createPayementForIndemnite( $data );
            if($isPaiementCreated !== true) return new JsonResponse(['error' => $isPaiementCreated], 500); 
            return new JsonResponse("Le paiement a été créé avec succès.", Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }
    
    #[Route('/indemnites-formateurs-paiement', name: 'app_formateurs_indemnites_paiement_get' , methods : ['GET'] )]
    public function obtenirPaiementsIndemnite(Request $request): JsonResponse
    {
        try{
            $indemniteId = $request->query->get('indemniteId') ?? null  ;
            $paiements = $this->obtenirPaiementService->obtenirPaiementsParIndemnite( $indemniteId );
            if($paiements === false) return new JsonResponse(['error' => $isPaiementCreated], 500);
            $json = json_decode($this->serializer->serialize($paiements, 'json', ['groups' => 'paiements-indemnites-formateures'])) ;
            $result = ['paiements' => $json ];
            return new JsonResponse($result, Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


        
    #[Route('/indemnites-formateurs-paiement', name: 'app_formateurs_indemnites_paiement_delete' , methods : ['DELETE'] )]
    public function supprimerPaiementIndemnite(Request $request): JsonResponse
    {
        try{
            $paiementId = $request->query->get('paiementId') ?? null  ;
            $isPaiementDeleted = $this->supprimerPaiementService->supprimerPaiement( $paiementId );
            if($isPaiementDeleted !== true) return new JsonResponse(['error' => $isPaiementDeleted], 500); 
            return new JsonResponse("Le paiement a été supprime avec succès.", Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => 'Une erreur est survenue.'], 500);
        }
    }


}
