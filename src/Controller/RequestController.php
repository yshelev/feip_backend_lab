<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Dto\RequestDto; 
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Service\RequestService; 

class RequestController extends AbstractController
{
    public function __construct(
        private readonly RequestService $requestService
    ) {}

    public function createRequest(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            throw new HttpException(422, "request body is empty"); 
        }
        $data = $request->toArray();         
        try {
            $requestDto = new RequestDto(...$data); 
        } catch (\Error $e) {
            throw new HttpException(422, "bad request data"); 
        }         
        try {
            $response = $this->requestService->createEntity($requestDto); 
        }
        catch (\Exception $e) {
            throw new HttpException(
                $e->getCode(), 
                $e->getMessage(), 
            ); 
        }

        
        return new JsonResponse($response["comment"], $response["status"]); 
    }

    public function changeRequest(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            throw new HttpException(
                422, 
                "request body is empty"
            );
        }
        $data = $request->toArray();  
        try {
            $requestDto = new RequestDto(...$data); 
        } catch (\Error $e) {
            throw new HttpException(
                422, 
                "bad data"
            );
        }       

        $response = $this->requestService->replaceRequest($requestDto);
        
        return new JsonResponse($response["comment"], $response["status"]); 
    }

    public function changeRequestComment(Request $request): JsonResponse
    {
        if (empty($request->getContent())) {
            throw new HttpException(
                422, 
                "request body is empty"
            );
        }

        $data = $request->toArray(); 
        if (empty($data["comment"]) || empty($data["id"])) {
            throw new HttpException(
                422, 
                "id and json required in body"
            );
        } 
        $comment = $data["comment"]; 
        $id = $data["id"];
        
        try {
            $this->requestService->changeRequestComment($id, $comment);     
        }
        catch (\Exception $e) {
            throw new HttpException(
                $e->getCode(),
                $e->getMessage()
            );
        }

        try {
            $response = $this->requestService->getOneRequestById($id);
        }
        catch (\Exception $e) {
            throw new HttpException(
                $e->getCode(),
                $e->getMessage() 
            ); 
        }
        $status = $response["status"]; 
        $responseValue = $response["value"];
        $responseComment = $response["comment"]; 

        return new JsonResponse([
            "comment" => $responseComment, 
            "value" => $responseValue 
        ], $status); 
    }
}