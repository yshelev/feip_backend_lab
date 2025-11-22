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
            throw new \Exception("error while fetching csv", 500); 
        }; 
        if ($response["value"] === null) {
            throw new \Exception("not found summer house with id: $id", 404);
        }

        return $response; 
    }

    public function isExistedWithId($id): bool {
        return $this->find($id)["value"] !== null; 
    }
}