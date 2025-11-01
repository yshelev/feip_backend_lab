<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\HouseRepository;
use App\Dto\CreateSummerHouseDto;
use App\Entity\House;
use Doctrine\ORM\EntityManagerInterface;

class SummerHouseService
{
    public function __construct(
        private HouseRepository $summerHouseRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function findAll(): array
    {
        $response = [];
        $houses = $this->summerHouseRepository->findAll();
        foreach ($houses as $house) {
            $response[] = new CreateSummerHouseDto(
                area: $house->getArea(),
                address: $house->getAddress(),
                price: $house->getPrice(),
                bedrooms: $house->getBedrooms(),
                distanceToSea: $house->getDistanceToSea(),
                hasShower: $house->hasShower(),
                hasBathroom: $house->hasBathroom()
            );
        }
        return $response;
    }

    public function find(int $id): ?CreateSummerHouseDto
    {
        $house = $this->summerHouseRepository->find($id);

        if ($house === null) {
            return null;
        }

        $response = new CreateSummerHouseDto(
            area: $house->getArea(),
            address: $house->getAddress(),
            price: $house->getPrice(),
            bedrooms: $house->getBedrooms(),
            distanceToSea: $house->getDistanceToSea(),
            hasShower: $house->hasShower(),
            hasBathroom: $house->hasBathroom()
        );


        return $response;
    }

    public function isExistedWithId($id): bool
    {
        return $this->find($id) !== null;
    }

    public function create(CreateSummerHouseDto $summerHouseDto): array
    {
        $house = House::create(
            area: $summerHouseDto->area,
            address: $summerHouseDto->address,
            price: $summerHouseDto->price,
            bedrooms: $summerHouseDto->bedrooms,
            distanceToSea: $summerHouseDto->distanceToSea,
            hasShower: $summerHouseDto->hasShower,
            hasBathroom: $summerHouseDto->hasBathroom
        );

        $this->entityManager->persist($house);
        $this->entityManager->flush();

        return ["status" => "ok"];
    }
}
