<?php

namespace App\Service;

use App\Repository\CsvSummerHouseRepository;
use App\Dto\SummerHouseDto; 

class SummerHouseService {
    private CsvSummerHouseRepository $summerHouseRepository;

    public function __construct(CsvSummerHouseRepository $summerHouseRepository)
    {
        $this->summerHouseRepository = $summerHouseRepository; 
    }

    public function findAll(): array {
        return $this->summerHouseRepository->findAll(); 
    }

    public function find(int $id): array {
        $response = [
            "status" => 200,
            "comment" => "OK", 
            "value" => null, 
        ]; 
        
        try {
            $house = $this->summerHouseRepository->find($id);
            $response["value"] = $house; 
        } catch (\Exception $e) {
            $response["value"] = null; 
            $response["comment"] = "error while fetching csv"; 
            $response["status"] = 500; 
            return $response; 
        }; 
        if ($response["value"] === null) {
            $response["comment"] = "not found summer house with id: $id"; 
            $response["status"] = 404; 
        }

        return $response; 
    }

    public function isExistedWithId($id): bool {
        return $this->find($id)["value"] !== null; 
    }
}