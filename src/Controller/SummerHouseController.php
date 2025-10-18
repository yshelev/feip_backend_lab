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
        private readonly SummerHouseService $summer_house_service
    ) {}

    public function get_all(): Response
    {
        return new JsonResponse($this->summer_house_service->findAll()); 
    }
}
