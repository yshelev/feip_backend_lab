<?php

namespace App\Controller;

use App\Service\SummerHouseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SummerHouseController extends AbstractController
{
    public function __construct(
        private readonly SummerHouseService $summerHouseService
    ) {}

    public function getAll(): Response
    {
        return new JsonResponse($this->summerHouseService->findAll()); 
    }
}
