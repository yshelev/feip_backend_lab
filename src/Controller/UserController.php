<?php

namespace App\Controller;

use App\Dto\CreateUserDto;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService 
    ) {}

    public function getOneUser(int $id): Response
    {
        $user = $this->userService->getUserById($id);
        if ($user === null) {
            return new JsonResponse([
                "phoneNumber" => null, 
            ], 404); 
        }
        
        return new JsonResponse([
            "phoneNumber" => $user->phoneNumber,
        ], 200); 
    }   

    public function createUser(Request $request): Response
    {
        $values = $request->toArray(); 
        $userDto = new CreateUserDto(
            $values["phoneNumber"],
            $values["password"],
        ); 

        $response = $this->userService->createUser($userDto); 
        if ($response["status"] === 0) {
            $value = $response["value"];
            return new JsonResponse([
                "id" => $value->getId(), 
                'pn' => $value->getPhoneNumber()
            ], 400);
        }
        return new JsonResponse([
            "message" => "successfully created"
        ], 201); 
    }

    public function getAllUsers(Request $request): Response {
        $response = $this->userService->getAllUsers(); 

        return new JsonResponse($response, 200); 
    }
}
