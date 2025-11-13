<?php

namespace App\Tests\Controller;

use App\Dto\CreateUserDto;
use App\Dto\UserResponseDto;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class UserControllerTest extends WebTestCase
{
    private $client;
    private $userServiceMock;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->userServiceMock = $this->createMock(UserService::class);
        $this->client->getContainer()->set(UserService::class, $this->userServiceMock);
    }

    public function testGetOneUserSuccess(): void
    {
        $user = new UserResponseDto(
            '+1234567890'
        );
        

        $this->userServiceMock
            ->method('getUserById')
            ->with(1)
            ->willReturn($user);

        $this->client->request('GET', '/user/1');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            '{"phoneNumber": "+1234567890"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testGetOneUserNotFound(): void
    {
        $this->userServiceMock
            ->method('getUserById')
            ->with(999)
            ->willReturn(null);

        $this->client->request('GET', '/user/999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJsonStringEqualsJsonString(
            '{"phoneNumber": null}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCreateUserSuccess(): void
    {
        $this->userServiceMock
            ->method('createUser')
            ->willReturn(['status' => 1]);

        $this->client->request(
            'POST',
            '/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"phoneNumber": "+1234567890"}'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJsonStringEqualsJsonString(
            '{"message": "successfully created"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCreateUserError(): void
    {
        $user = new User();
        $user->setId(1);
        $user->setPhoneNumber('+1234567890'); 

        $this->userServiceMock
            ->method('createUser')
            ->willReturn([
                'status' => 0,
                'value' => $user
            ]);

        $this->client->request(
            'POST',
            '/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"phoneNumber": "+1234567890"}'
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        
        $this->assertEquals(1, $responseContent['id']);
        $this->assertEquals('+1234567890', $responseContent['pn']);
    }

    public function testGetAllUsers(): void
    {
        $users = [
            ['id' => 1, 'phoneNumber' => '+111111111'],
            ['id' => 2, 'phoneNumber' => '+222222222']
        ];

        $this->userServiceMock
            ->method('getAllUsers')
            ->willReturn($users);

        $this->client->request('GET', '/user');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            json_encode($users),
            $this->client->getResponse()->getContent()
        );
    }

    public function testIndex(): void
    {
        $this->userServiceMock
            ->method('getAllUsers')
            ->willReturn([]);

        $this->client->request('GET', '/user');

        self::assertResponseIsSuccessful();
    }
}