<?php

namespace App\Controller;

use Exception;
use App\Entity\DemandeInscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class DemandeInscriptionController extends AbstractController
{
    private $entityManager ;
    private $passwordEncoder ;
    public function __construct(EntityManagerInterface $entityManager ,  UserPasswordHasherInterface  $passwordEncoder,){
        $this->entityManager = $entityManager ;
        $this->passwordEncoder = $passwordEncoder ;

    }
    
    #[Route('/demande/inscription', name: 'app_demande_inscription' , methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try{
            $data = json_decode($request->getContent(), true);
            $demande = new DemandeInscription ;
            // TODO : Il faut que j'utilise une fonction comme htmlspecialchars avant de stocker les données dans la base de données.
            $demande->setNom($data['nom']);
            $demande->setPrenom($data['prenom']);
            $demande->setEmail($data['email']);
            $demande->setNumeroTelephone($data['numeroTelephone']);
            $demande->setMotDePass($data['motDePass']);
            $this->entityManager->persist($demande);
            // $this->entityManager->flush(); 
            return new JsonResponse(['message' => 'Demande d’inscription bien créée '], 201);
        }
        catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }

    #[Route('/demande/inscription/validate', name: 'app_demande_inscription_validate' )]
    public function validate(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new Users ; 
        $user->setEmail($data['email'] ?? '');
        $user->setPassword($this->passwordEncoder->hashPassword($user, $data['password'] ?? ''));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse(['message' => 'User registered successfully'], 201);
    }
}
