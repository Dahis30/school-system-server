<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UsersController extends AbstractController
{
    private $serializerInterface ;
    public function __construct(SerializerInterface $serializerInterface){
        $this->serializerInterface = $serializerInterface ;

    }
    #[Route('/user-information', name: 'app_users_get_info')]
    public function getUserInformation(): JsonResponse
    {
        try{
            $userObject = $this->getUser() ;
            $userInfo = $this->serializerInterface->serialize($userObject, 'json', ['groups' => 'user-information']);
            return new JsonResponse(json_decode( $userInfo ), Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }
}
