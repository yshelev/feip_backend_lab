<?php

namespace App\Tests\Service;

use App\Dto\CreateRequestDto;
use App\Entity\Request;
use App\Entity\User;
use App\Entity\House;
use App\Repository\HouseRepository;
use App\Repository\RequestRepository;
use App\Repository\UserRepository;
use App\Service\RequestService;
use App\Service\SummerHouseService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class RequestServiceTest extends TestCase
{
    private RequestService $requestService;
    private $entityManagerMock;
    private $requestRepositoryMock;
    private $houseRepositoryMock;
    private $userRepositoryMock;
    private $summerHouseServiceMock;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->requestRepositoryMock = $this->createMock(RequestRepository::class);
        $this->houseRepositoryMock = $this->createMock(HouseRepository::class);
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->summerHouseServiceMock = $this->createMock(SummerHouseService::class);

        $this->requestService = new RequestService(
            $this->entityManagerMock,
            $this->requestRepositoryMock,
            $this->houseRepositoryMock,
            $this->userRepositoryMock,
            $this->summerHouseServiceMock
        );
    }

    public function testCreateEntitySuccess(): void
    {
        $createRequestDto = new CreateRequestDto(
            comment: 'Test comment',
            houseId: 1,
            phoneNumber: '+1234567890'
        );

        $user = new User();
        $house = new House();
        $expectedRequest = new Request();

        $this->houseRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($house);

        $this->userRepositoryMock
            ->expects($this->once())
            ->method('findByPhoneNumber')
            ->with('+1234567890')
            ->willReturn($user);

        $requestMock = $this->createMock(Request::class);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Request::class));

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->requestService->createEntity($createRequestDto);

        $this->assertInstanceOf(Request::class, $result);
    }

    public function testChangeRequestCommentSuccess(): void
    {
        $id = 1;
        $newComment = 'Updated comment';
        $request = new Request();

        $this->requestRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($request);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->requestService->changeRequestComment($id, $newComment);

        $this->assertSame($request, $result);
        $this->assertEquals($newComment, $request->getComment());
    }

    public function testGetOneRequestByIdSuccess(): void
    {
        $id = 1;
        $expectedRequest = new Request();

        $this->requestRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedRequest);

        $result = $this->requestService->getOneRequestById($id);

        $this->assertSame($expectedRequest, $result);
    }

    public function testGetOneRequestByIdNotFound(): void
    {
        $id = 999;

        $this->requestRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $result = $this->requestService->getOneRequestById($id);

        $this->assertNull($result);
    }
}
