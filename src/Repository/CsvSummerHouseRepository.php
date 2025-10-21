<?php 

namespace App\Repository;

use App\Dto\SummerHouseDto;

class CsvSummerHouseRepository extends CsvAbstractRepository
{  
    public function findAll(): array {
        $requests = []; 

        $rawData = $this->getCsvData(); 

        foreach($rawData as $data) {
            $requests[] = new SummerHouseDto(
                (int)$data[0], 
                (float)$data[1], 
                $data[2], 
                (int)$data[3], 
                (int)$data[4], 
                (int)$data[5], 
                (bool)$data[6], 
                (bool)$data[7]
            ); 
        };

        return $requests;  
    }

    public function find(int $id): ?SummerHouseDto {
        $rawData = $this->getCsvData();

        foreach($rawData as $data) {
            if ($data[0] == $id) {
                return new SummerHouseDto(
                    (int)$data[0], 
                    (float)$data[1], 
                    $data[2], 
                    (int)$data[3], 
                    (int)$data[4], 
                    (int)$data[5], 
                    (bool)$data[6], 
                    (bool)$data[7]
                ); 
            }
        }

        return null;
    }

    public function create($entity) {
        $this->addRawDataToCsv($entity->toArray());
    }

    public function delete(int $id): bool {
        return $this->deleteRowById($id); 
    }
}