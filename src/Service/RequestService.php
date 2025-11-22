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
            throw new \Exception("summer house with $entity->house_id not found", 404);
        } 

        try {
            $value = $this->requestRepository->create($entity); 
        } catch (\Exception $e) {
            throw new \Exception("error while fetching csv", 500);
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
            throw new \Exception("error while fetching csv", 500); 
        }; 

        
        if ($request === null) {
            throw new \Exception("request with $id not found", 404); 
        } 

        $request->comment = $comment; 
        try {
            $response = $this->replaceRequest($request); 
        }
        catch (\Exception $e) {
            throw new \Exception(
                $e->getMessage(), 
                $e->getCode()
            ); 
        }; 

        return $response; 
    }

    public function replaceRequest(RequestDto $entity): array {
        $response = [
            "status" => 202, 
            "comment" => "Request replaced"
        ];

        if (!$this->summerHouseService->isExistedWithId($entity->houseId)) {
            throw new \Exception("summer house with $entity->houseId not found", 404); 
        } 
        
        $id = $entity->id; 

        try {
            $this->requestRepository->delete($id); 
            $this->requestRepository->create($entity);
        } catch (\Exception $e) {
            throw new \Exception(
                "error while fetching csv", 
                500
            ); 
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
            throw new \Exception("error while fetching csv", 500); 
        }

        if ($value === null) {
            throw new \Exception("not found request wth id $id", 404);
        }

        $response["value"] = $value; 
        return $response; 
    }
}