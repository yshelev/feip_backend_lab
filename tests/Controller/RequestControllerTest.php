<?php

namespace App\Tests\Controller;

use App\Dto\CreateRequestDto;
use App\Dto\UpdateRequestDto;
use App\Service\RequestService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class RequestControllerTest extends WebTestCase
{
    private $client;
    private $requestServiceMock;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->requestServiceMock = $this->createMock(RequestService::class);

        $container = static::getContainer();
        $container->set(RequestService::class, $this->requestServiceMock);
    }

    public function testCreateRequestSuccess(): void
    {
        $requestData = new CreateRequestDto(
            comment: "omment",
            phoneNumber: "+79999999999",
            houseId: 1
        );

        $this->requestServiceMock
            ->expects($this->once())
            ->method('createEntity')
            ->with($this->isInstanceOf(CreateRequestDto::class));

        $this->client->request(
            'POST',
            '/request',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEquals('{}', $this->client->getResponse()->getContent());
    }

    public function testCreateRequestEmptyBody(): void
    {
        $this->client->request(
            'POST',
            '/request',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertJsonStringEqualsJsonString(
            '{"comment": "request body is empty"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCreateRequestBadData(): void
    {
        $invalidData = [
            'invalid' => 'data'
        ];

        $this->client->request(
            'POST',
            '/request',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invalidData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertJsonStringEqualsJsonString(
            '{"comment": "bad data", "value": null}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testChangeRequestSuccess(): void
    {
        $requestData = [
            'id' => 1,
            'comment' => 'updated_value'
        ];

        $this->client->request(
            'PATCH',
            '/request',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testChangeRequestEmptyBody(): void
    {
        $this->client->request(
            'PATCH',
            '/request',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ''
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertJsonStringEqualsJsonString(
            '{"comment": "request body is empty"}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testChangeRequestBadData(): void
    {
        $invalidData = [
            'invalid' => 'data'
        ];

        $this->client->request(
            'PATCH',
            '/request',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($invalidData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->assertJsonStringEqualsJsonString(
            '{"comment":"id and json required in body"}',
            $this->client->getResponse()->getContent()
        );
    }
}
