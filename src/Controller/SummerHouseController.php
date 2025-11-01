<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CreateSummerHouseDto;
use App\Service\SummerHouseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

final class SummerHouseController extends AbstractController
{
    public function __construct(
        private readonly SummerHouseService $summerHouseService
    ) {
    }

    public function getAll(): Response
    {
        return new JsonResponse($this->summerHouseService->findAll());
    }

    public function create(Request $request): Response
    {
        $rArray = $request->toArray();

        $keys = [
            'area', 'address', 'price', 'bedrooms', 'distanceToSea', 'hasShower', 'hasBathroom'
        ];

        foreach ($keys as $key) {
            if (empty($rArray[$key])) {
                return new JsonResponse(['data' => "missing key: $key"], 422); 
            }
        }

        $houseDto = new CreateSummerHouseDto(
            area: $rArray['area'] ?? 0,
            address: $rArray['address'] ?? "",
            price: $rArray['price'] ?? 0,
            bedrooms: $rArray['bedrooms'] ?? 0,
            distanceToSea: $rArray['distanceToSea'] ?? 0,
            hasShower: $rArray['hasShower'] ?? false,
            hasBathroom: $rArray['hasBathroom'] ?? true
        );

        $this->summerHouseService->create($houseDto);

        return new JsonResponse(status: 201);
    }

    public function getById(int $id): Response
    {
        $response = $this->summerHouseService->find($id);

        if ($response === null) {
            return new JsonResponse(status: 404);
        }

        return new JsonResponse($response);
    }
}
