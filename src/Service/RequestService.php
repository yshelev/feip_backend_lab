<?php

namespace App\Service;

use App\Repository\CsvRequestRepository;
use App\Dto\RequestDto; 

class RequestService {

    public function __construct(
        private readonly CsvRequestRepository $requestRepository, 
        private readonly SummerHouseService $summer_house_service
    ) {}

    public function create_entity($entity): array {
        $response = [
            "comment" => "OK", 
            "status" => 201, 
            "value" => null
        ];

        if (!$this->summer_house_service->isExistedWithId($entity->house_id)) {
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

    public function change_request_comment(int $id, string $comment): array {
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
        
        $response = $this->replace_request($request); 

        return $response; 
    }

    public function replace_request(RequestDto $entity): array {
        $response = [
            "status" => 202, 
            "comment" => "Request replaced"
        ];

        if (!$this->summer_house_service->isExistedWithId($entity->house_id)) {
            $response["status"] = 404; 
            $response["comment"] = "summer house with $entity->house_id not found";
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

    public function get_one_request_by_id(int $id): array {
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