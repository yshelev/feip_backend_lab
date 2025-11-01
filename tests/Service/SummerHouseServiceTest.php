<?php

namespace App\Tests\Service;

use App\Repository\HouseRepository;
use App\Dto\CreateSummerHouseDto;
use App\Entity\House;
use App\Service\SummerHouseService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SummerHouseServiceTest extends TestCase
{
    private SummerHouseService $summerHouseService;
    private $houseRepositoryMock;
    private $entityManagerMock;

    protected function setUp(): void
    {
        $this->houseRepositoryMock = $this->createMock(HouseRepository::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);

        $this->summerHouseService = new SummerHouseService(
            $this->houseRepositoryMock,
            $this->entityManagerMock
        );
    }

    public function testFindAll(): void
    {
        $house1 = $this->createHouseMock(100, 'Address 1', 50000, 3, 200, true, true);
        $house2 = $this->createHouseMock(150, 'Address 2', 75000, 4, 150, true, false);

        $this->houseRepositoryMock
            ->method('findAll')
            ->willReturn([$house1, $house2]);

        $result = $this->summerHouseService->findAll();

        $this->assertCount(2, $result);
        $this->assertInstanceOf(CreateSummerHouseDto::class, $result[0]);
        $this->assertInstanceOf(CreateSummerHouseDto::class, $result[1]);
        $this->assertEquals(100, $result[0]->area);
        $this->assertEquals('Address 1', $result[0]->address);
        $this->assertEquals(50000, $result[0]->price);
        $this->assertEquals(3, $result[0]->bedrooms);
        $this->assertEquals(200, $result[0]->distanceToSea);
        $this->assertTrue($result[0]->hasShower);
        $this->assertTrue($result[0]->hasBathroom);
    }

    public function testFindAllWithEmptyResult(): void
    {
        $this->houseRepositoryMock
            ->method('findAll')
            ->willReturn([]);

        $result = $this->summerHouseService->findAll();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testFindSuccess(): void
    {
        $houseId = 1;
        $house = $this->createHouseMock(100, 'Test Address', 50000, 3, 200, true, true);

        $this->houseRepositoryMock
            ->method('find')
            ->with($houseId)
            ->willReturn($house);

        $result = $this->summerHouseService->find($houseId);

        $this->assertInstanceOf(CreateSummerHouseDto::class, $result);
        $this->assertEquals(100, $result->area);
        $this->assertEquals('Test Address', $result->address);
        $this->assertEquals(50000, $result->price);
        $this->assertEquals(3, $result->bedrooms);
        $this->assertEquals(200, $result->distanceToSea);
        $this->assertTrue($result->hasShower);
        $this->assertTrue($result->hasBathroom);
    }

    public function testFindNotFound(): void
    {
        $houseId = 999;

        $this->houseRepositoryMock
            ->method('find')
            ->with($houseId)
            ->willReturn(null);

        $result = $this->summerHouseService->find($houseId);

        $this->assertNull($result);
    }

    public function testIsExistedWithIdWhenExists(): void
    {
        $houseId = 1;
        $house = $this->createHouseMock(100, 'Test Address', 50000, 3, 200, true, true);

        $this->houseRepositoryMock
            ->method('find')
            ->with($houseId)
            ->willReturn($house);

        $result = $this->summerHouseService->isExistedWithId($houseId);

        $this->assertTrue($result);
    }

    public function testIsExistedWithIdWhenNotExists(): void
    {
        $houseId = 999;

        $this->houseRepositoryMock
            ->method('find')
            ->with($houseId)
            ->willReturn(null);

        $result = $this->summerHouseService->isExistedWithId($houseId);

        $this->assertFalse($result);
    }

    public function testCreate(): void
    {
        $summerHouseDto = new CreateSummerHouseDto(
            area: 100,
            address: 'New Address',
            price: 50000,
            bedrooms: 3,
            distanceToSea: 200,
            hasShower: true,
            hasBathroom: true
        );

        $this->entityManagerMock
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(House::class));

        $this->entityManagerMock
            ->expects($this->once())
            ->method('flush');

        $result = $this->summerHouseService->create($summerHouseDto);

        $this->assertEquals(['status' => 'ok'], $result);
    }

    private function createHouseMock(
        float $area,
        string $address,
        int $price,
        int $bedrooms,
        int $distanceToSea,
        bool $hasShower,
        bool $hasBathroom
    ) {
        $house = $this->createMock(House::class);
        $house->method('getArea')->willReturn($area);
        $house->method('getAddress')->willReturn($address);
        $house->method('getPrice')->willReturn($price);
        $house->method('getBedrooms')->willReturn($bedrooms);
        $house->method('getDistanceToSea')->willReturn($distanceToSea);
        $house->method('hasShower')->willReturn($hasShower);
        $house->method('hasBathroom')->willReturn($hasBathroom);

        return $house;
    }
}
