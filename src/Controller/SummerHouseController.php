<?php

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
    ) {}

    public function getAll(): Response
    {
        return new JsonResponse($this->summerHouseService->findAll()); 
    }

    public function create(Request $request): Response
    {
        $rArray = $request->toArray(); 

        $houseDto = new CreateSummerHouseDto(
            area: $rArray['area'],
            address: $rArray['address'],
            price: $rArray['price'], 
            bedrooms: $rArray['bedrooms'],
            distanceToSea: $rArray['distanceToSea'], 
            hasShower: $rArray['hasShower'],
            hasBathroom: $rArray['hasBathroom']
        ); 

        $response = $this->summerHouseService->create($houseDto); 
        if ($response === null) {
            return new JsonResponse(status: 404); 
        }

        return new JsonResponse(status: 201); 
    }

    public function getById(int $id): Response {
        $response = $this->summerHouseService->find($id);

        if ($response === null) {
            return new JsonResponse(status: 404); 
        }

        return new JsonResponse($response); 
    } 
}
