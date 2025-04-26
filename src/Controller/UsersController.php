<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UsersController extends AbstractController
{
    private $serializerInterface ;
    private $usersRepository ;
    public function __construct(SerializerInterface $serializerInterface , UsersRepository $usersRepository){
        $this->serializerInterface = $serializerInterface ;
        $this->usersRepository = $usersRepository ;

    }
    #[Route('/user-information', name: 'app_user_get_info')]
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

    #[Route('/users-information', name: 'app_users_get_info')]
    public function getAllUsersInformation(): JsonResponse
    {
        try{
            $usersData = $this->usersRepository->findAll() ;
            $specifiedUsers = [];
            foreach( $usersData as $user){
                if( in_array( 'ROLE_USER' ,$user->getRoles()) )  $specifiedUsers [] = $user  ;
            }
            $usersInfo = $this->serializerInterface->serialize($specifiedUsers, 'json', ['groups' => 'user-information']);
            $result = ['users' =>json_decode($usersInfo) , 'total'=> count($specifiedUsers)  ];
            return new JsonResponse(( $result ), Response::HTTP_OK, []);
        }catch(Exception $e){
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

    }
}
