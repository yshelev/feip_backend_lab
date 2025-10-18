<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Dto\RequestDto; 
use App\Service\RequestService; 

class RequestController extends AbstractController
{
    public function __construct(
        private readonly RequestService $requestService
    ) {}

    public function create_request(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse([
                "comment" => "request body is empty"
            ], 400); 
        }
        $data = $request->toArray();         
        try {
            $requestDto = new RequestDto(...$data); 
        } catch (\Error $e) {
            return new JsonResponse([
                "comment" => "bad data", 
                "value" => null
            ], 400); 
        }         
        
        $response = $this->requestService->create_entity($requestDto); 
        
        return new JsonResponse($response["comment"], $response["status"]); 
    }

    public function change_request(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse([
                "comment" => "request body is empty"
            ], 400); 
        }
        $data = $request->toArray();  
        try {
            $requestDto = new RequestDto(...$data); 
        } catch (\Error $e) {
            return new JsonResponse([
                "comment" => "bad data"
            ], 400); 
        }       

        $response = $this->requestService->replace_request($requestDto);
        
        return new JsonResponse($response["comment"], $response["status"]); 
    }

    public function change_request_comment(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            return new JsonResponse([
                "comment" => "request body is empty"
            ], 400); 
        }

        $data = $request->toArray(); 
        try {
            $comment = $data["comment"]; 
            $id = $data["id"];
        } catch (\Exception $e) {
            $response = [
                "comment" => "comment and id required in body", 
            ]; 
            return new JsonResponse($response, 400); 
        }
        
        $this->requestService->change_request_comment($id, $comment); 

        $response = $this->requestService->get_one_request_by_id($id);

        $status = $response["status"]; 
        $response_value = $response["value"];
        if ($response_value !== null) {
            $response_value = $response_value->to_array(); 
        };
        $response_comment = $response["comment"]; 

        return new JsonResponse([
            "comment" => $response_comment, 
            "value" => $response_value 
        ], $status); 
    }
}