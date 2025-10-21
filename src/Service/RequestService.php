<?php

namespace App\Service;

use App\Repository\CsvRequestRepository;
use App\Dto\RequestDto; 

class RequestService {

    public function __construct(
        private readonly CsvRequestRepository $requestRepository, 
        private readonly SummerHouseService $summerHouseService
    ) {}

    public function createEntity($entity): array {
        $response = [
            "comment" => "OK", 
            "status" => 201, 
            "value" => null
        ];

        if (!$this->summerHouseService->isExistedWithId($entity->house_id)) {
            $response["status"] = 404; 
            $response["comment"] = "summer house with $entity->house_id not found"; 
            return $response;
        } 

        try {
            $value = $this->requestRepository->create($entity); 
        } catch (\Exception $e) {
            $response["comment"] = "error while fetching csv"; 
            $response["status"] = 500; 
            return $response;
        }; 

        $response["value"] = $value; 
        return $response;
    }

    public function changeRequestComment(int $id, string $comment): array {
        $response = [
            "status" => 202, 
            "comment" => "Accepted"
        ]; 
        
        try {
            $request = $this->requestRepository->find($id);
        } catch (\Exception $e) {
            $response["status"] = 500; 
            $response["comment"] = "error while fetching csv";
            return $response; 
        }; 

        
        if ($request === null) {
            $response["status"] = 404;
            $response["comment"] = "request with $id not found"; 
            return $response; 
        } 

        $request->comment = $comment; 
        
        $response = $this->replaceRequest($request); 

        return $response; 
    }

    public function replaceRequest(RequestDto $entity): array {
        $response = [
            "status" => 202, 
            "comment" => "Request replaced"
        ];

        if (!$this->summerHouseService->isExistedWithId($entity->houseId)) {
            $response["status"] = 404; 
            $response["comment"] = "summer house with $entity->houseId not found";
            return $response;  
        } 
        
        $id = $entity->id; 

        try {
            $this->requestRepository->delete($id); 
            $this->requestRepository->create($entity);
        } catch (\Exception $e) {
            $response["status"] = 500; 
            $response["comment"] = "error while fetching csv"; 
            return $response; 
        } 
        
        return $response; 
    }

    public function getOneRequestById(int $id): array {
        $response = [
            "comment" => "OK", 
            "status" => 200, 
            "value" => []
        ];

        try {
            $value = $this->requestRepository->find($id);
        } catch (\Exception $e) {
            $response["status"] = 500; 
            $response["comment"] = "error while fetching csv"; 
            $response["value"] = null; 
            return $response;  
        }

        $response["value"] = $value; 
        return $response; 
    }
}