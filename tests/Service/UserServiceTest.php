<?php

namespace App\Tests\Service;

use App\Dto\CreateUserDto;
use App\Dto\UserResponseDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;
    private $entityManagerMock;
    private $userRepositoryMock;
    private $passwordHasher; 

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $this->userService = new UserService(
            $this->entityManagerMock,
            $this->userRepositoryMock, 
            $this->passwordHasher
        );
    }

    public function testCreateUserSuccess(): void
    {
        $userData = new CreateUserDto(
            '+1234567890', 
            "qwe"
        );

        $this->userRepositoryMock
            ->method('findByPhoneNumber')
            ->with('+1234567890')
            ->willReturn(null);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->userService->createUser($userData);

        $this->assertEquals([
            "status" => 1,
            "message" => "OK"
        ], $result);
    }

    public function testCreateUserAlreadyExists(): void
    {
        $userData = new CreateUserDto(
            '+1234567890', 
            "qwe"
        );
        $existingUser = new User();

        $this->userRepositoryMock
            ->method('findByPhoneNumber')
            ->with('+1234567890')
            ->willReturn($existingUser);

        $this->entityManagerMock
            ->expects($this->never())
            ->method('persist');

        $this->entityManagerMock
            ->expects($this->never())
            ->method('flush');

        $result = $this->userService->createUser($userData);

        $this->assertEquals([
            "status" => 0,
            "value" => $existingUser
        ], $result);
    }

    public function testGetUserByIdFound(): void
    {
        $userId = 1;
        $user = $this->createMock(User::class);
        $user->method('getPhoneNumber')->willReturn('+1234567890');

        $this->userRepositoryMock
            ->method('findById')
            ->with($userId)
            ->willReturn($user);

        $result = $this->userService->getUserById($userId);

        $this->assertInstanceOf(UserResponseDto::class, $result);
        $this->assertEquals('+1234567890', $result->phoneNumber);
    }

    public function testGetUserByIdNotFound(): void
    {
        $userId = 999;

        $this->userRepositoryMock
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $result = $this->userService->getUserById($userId);

        $this->assertNull($result);
    }

    public function testGetAllUsers(): void
    {
        $user1 = $this->createMock(User::class);
        $user1->method('getPhoneNumber')->willReturn('+111111111');

        $user2 = $this->createMock(User::class);
        $user2->method('getPhoneNumber')->willReturn('+222222222');

        $this->userRepositoryMock
            ->method('findAll')
            ->willReturn([$user1, $user2]);

        $result = $this->userService->getAllUsers();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(UserResponseDto::class, $result[0]);
        $this->assertInstanceOf(UserResponseDto::class, $result[1]);
        $this->assertEquals('+111111111', $result[0]->phoneNumber);
        $this->assertEquals('+222222222', $result[1]->phoneNumber);
    }

    public function testGetAllUsersEmpty(): void
    {
        $this->userRepositoryMock
            ->method('findAll')
            ->willReturn([]);

        $result = $this->userService->getAllUsers();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}