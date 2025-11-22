<?php
namespace App\Repository;

use App\Dto\RequestDto;

class CsvRequestRepository extends CsvAbstractRepository
{  
    public function findAll(): array {
        $requests = []; 

        $rawData = $this->getCsvData(); 

        foreach($rawData as $data) {
            $id = (int)$data[0]; 
            $houseId = (int)$data[1]; 
            $phoneNumber = $data[2]; 
            $comment = $data[3];
            $requests[] = new RequestDto(
                id: $id, 
                houseId: $houseId, 
                phoneNumber: $phoneNumber, 
                comment: $comment
            ); 
        };

        return $requests;  
    }

    public function find(int $id): ?RequestDto {
        $rawData = $this->getCsvData();

        $requestDto = null; 

        foreach($rawData as $data) {
            if ($data[0] == $id) {
                $houseId = (int)$data[1]; 
                $phoneNumber = $data[2]; 
                $comment = $data[3];
                $requestDto = new RequestDto(
                    id: $id, 
                    houseId: $houseId, 
                    phoneNumber: $phoneNumber, 
                    comment: $comment
                ); 
                break;
            }
        }

        return $requestDto;
    }

    public function create($entity) {
        $this->addRawDataToCsv($entity->toArray());
    }

    public function delete(int $id): bool {
        return $this->deleteRowById($id); 
    }
}