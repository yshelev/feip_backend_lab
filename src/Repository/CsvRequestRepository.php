<?php
namespace App\Repository;

use App\Dto\RequestDto;

class CsvRequestRepository extends CsvAbstractRepository
{  
    public function findAll(): array {
        $requests = []; 

        $rawData = $this->getCsvData(); 

        foreach($rawData as $data) {
            $requests[] = new RequestDto(
                (int)$data[0], 
                (int)$data[1], 
                $data[2], 
                $data[3]
            ); 
        };

        return $requests;  
    }

    public function find(int $id): ?RequestDto {
        $rawData = $this->getCsvData();

        foreach($rawData as $data) {
            if ($data[0] == $id) {
                return new RequestDto(
                    (int)$data[0], 
                    (int)$data[1], 
                    $data[2], 
                    $data[3]
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