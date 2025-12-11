<?php

namespace App\Tests\Controller;

use App\Dto\CreateSummerHouseDto;
use App\Service\SummerHouseService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User; // ДОБАВЛЕНО

class SummerHouseControllerTest extends WebTestCase
{
    private $client;
    private $summerHouseServiceMock;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->summerHouseServiceMock = $this->createMock(SummerHouseService::class);
        
        $container = static::getContainer();
        $container->set(SummerHouseService::class, $this->summerHouseServiceMock);
        
        $this->authenticateUser();
    }
    
    private function authenticateUser(): void
    {
        $entityManager = static::getContainer()->get('doctrine')->getManager();
        $userRepository = $entityManager->getRepository(User::class);
        
        $user = $userRepository->findOneBy(['phoneNumber' => '+1234567890']);
        
        if (!$user) {
            $user = new User();
            $user->setPhoneNumber('+1234567890');
            $user->setPassword('testpassword');
            $user->setRoles(['ROLE_USER']);
            
            $entityManager->persist($user);
            $entityManager->flush();
        }
        
        $this->client->loginUser($user);
    }

    public function testGetAll(): void
    {
        $mockData = [
            ['id' => 1, 'area' => 100, 'address' => 'Test Address 1'],
            ['id' => 2, 'area' => 150, 'address' => 'Test Address 2']
        ];

        $this->summerHouseServiceMock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($mockData);

        $this->client->request('GET', '/summer-house');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            json_encode($mockData),
            $this->client->getResponse()->getContent()
        );
    }

    public function testGetByIdSuccess(): void
    {
        $mockData = new CreateSummerHouseDto(...[
            'area' => 100,
            'address' => 'Test Address',
            'price' => 50000,
            'bedrooms' => 3,
            'distanceToSea' => 200,
            'hasShower' => true,
            'hasBathroom' => true
        ]);

        $this->summerHouseServiceMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($mockData);

        $this->client->request('GET', '/summer-house/1');

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(
            json_encode($mockData),
            $this->client->getResponse()->getContent()
        );
    }

    public function testGetByIdNotFound(): void
    {
        $this->summerHouseServiceMock
            ->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $this->client->request('GET', '/summer-house/999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreateSuccess(): void
    {
        $this->summerHouseServiceMock
            ->expects($this->once())
            ->method('create')
            ->willReturn(['id' => 1]); 

        $this->client->request(
            'POST',
            '/summer-house',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'area' => 100,
                'address' => 'Test Address',
                'price' => 50000,
                'bedrooms' => 3,
                'distanceToSea' => 200,
                'hasShower' => true,
                'hasBathroom' => true
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testCreateFailure(): void
    {
        $this->summerHouseServiceMock
            ->expects($this->once())
            ->method('create'); 

        $this->client->request(
            'POST',
            '/summer-house',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'area' => 100,
                'address' => 'Test Address',
                'price' => 50000,
                'bedrooms' => 3,
                'distanceToSea' => 200,
                'hasShower' => true,
                'hasBathroom' => true
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}