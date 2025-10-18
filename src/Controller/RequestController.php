<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RequestController extends AbstractController
{
    #[Route('/request/{id}', name: 'request_hello')]
    public function hello(string $name): JsonResponse
    {
        
        return new JsonResponse(['message' => "Hello, $name!"]);
    }
}