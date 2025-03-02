<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Service\FormationService;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/formation', name: 'app_formation')]
    public function getFormations(): JsonResponse
    {
        // $formation = new Formation ;
        // $formation->setTitre('Comptable junior');
        // $formation->setDescription('Comptable junior');
        // $formation->setNombreDesMois(10);
        // $formation->setUtilisateur($this->getUser());
        // $this->entityManager->persist($formation);
        // $this->entityManager->flush();

        $formationsData = $this->formationService->getFormationsByUser($this->getUser()) ;
        $result = ['formations' => json_decode($this->serializer->serialize($formationsData, 'json', ['groups' => 'formation']))];
        return new JsonResponse($result, Response::HTTP_OK, []);

    }
}
