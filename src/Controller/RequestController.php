<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Dto\CreateRequestDto; 
use App\Dto\UpdateRequestDto; 
use App\Service\RequestService; 

class RequestController extends AbstractController
{
    public function __construct(
        private readonly RequestService $requestService
    ) {}

    public function createRequest(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse([
                "comment" => "request body is empty"
            ], 422); 
        }
        $data = $request->toArray();         
        try {
            $requestDto = new CreateRequestDto(...$data); 
        } catch (\Error $e) {
            return new JsonResponse([
                "comment" => "bad data", 
                "value" => null
            ], 422); 
        }         
        
        $this->requestService->createEntity($requestDto); 
        
        return new JsonResponse(status: 201); 
    }

    public function changeRequest(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse([
                "comment" => "request body is empty"
            ], 422); 
        }
        $data = $request->toArray();  
        try {
            $requestDto = new UpdateRequestDto(...$data); 
        } catch (\Error $e) {
            return new JsonResponse([
                "comment" => "bad data"
            ], 422); 
        }       

        $response = $this->requestService->replaceRequest($requestDto);
        
        return new JsonResponse($response["comment"], $response["status"]); 
    }

    public function changeRequestComment(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse([
                "comment" => "request body is empty"
            ], 422); 
        }

        $data = $request->toArray(); 
        if (empty($data["comment"]) || empty($data["id"])) {
            return new JsonResponse([
                "comment" => "id and json required in body", 
            ], 422);
        } 
        $comment = $data["comment"]; 
        $id = $data["id"];
        
        $this->requestService->changeRequestComment($id, $comment); 

        return new JsonResponse(status: 202); 
    }
}