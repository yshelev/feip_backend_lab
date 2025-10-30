<?php

namespace App\Service;

use App\Dto\CreateUserDto;
use App\Dto\UserResponseDto;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private UserRepository $userRepository
    ) {}

    public function createUser(CreateUserDto $userData) : mixed{
        $userToCheck = $this->userRepository->findByPhoneNumber($userData->phoneNumber); 
        if ($userToCheck !== null) {
            return [
                "status" => 0, 
                "value" => $userToCheck
            ]; 
        }

        $user = User::create(
            $userData->phoneNumber
        ); 

        $this->entityManager->persist($user); 
        $this->entityManager->flush(); 

        return [
            "status" => 1, 
            "message" => "OK"
        ]; 
    }

    public function getUserById(int $id) : ?UserResponseDto{
        $user = $this->userRepository->findById($id);
        
        if ($user === null) {
            return null; 
        }

        $responseDto = new UserResponseDto(
            $user->getPhoneNumber()
        );

        return $responseDto; 
    }

    public function getAllUsers() : array {
        $response = []; 
        $users = $this->userRepository->findAll(); 

        foreach ($users as $user) {
            $response[] = new UserResponseDto(
                phoneNumber: $user->getPhoneNumber()
            ); 
        }


        return $response; 
    }
}