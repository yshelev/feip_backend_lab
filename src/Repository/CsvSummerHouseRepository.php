<?php 

namespace App\Repository;

use App\Dto\SummerHouseDto;

class CsvSummerHouseRepository extends CsvAbstractRepository
{  
    public function findAll(): array {
        $requests = []; 

        $rawData = $this->getCsvData(); 


        foreach($rawData as $data) {
            $id = (int)$data[0]; 
            $area = (int)$data[1]; 
            $address = (int)$data[2]; 
            $price = (int)$data[3]; 
            $bedrooms = (int)$data[4]; 
            $distanceToSea = (int)$data[5]; 
            $hasShower = (int)$data[6]; 
            $hasBathroom = (int)$data[7];
            $requests[] = new SummerHouseDto(
                id: $id, 
                area: $area, 
                address: $address, 
                price: $price, 
                bedrooms: $bedrooms, 
                distanceToSea: $distanceToSea, 
                hasShower: $hasShower, 
                hasBathroom: $hasBathroom
            ); 
        };

        return $requests;  
    }

    public function find(int $id): ?SummerHouseDto {
        $rawData = $this->getCsvData();

        $summerHouseDto = null; 

        foreach($rawData as $data) {
            if ($data[0] == $id) {
                $area = (int)$data[1]; 
                $address = (int)$data[2]; 
                $price = (int)$data[3]; 
                $bedrooms = (int)$data[4]; 
                $distanceToSea = (int)$data[5]; 
                $hasShower = (int)$data[6]; 
                $hasBathroom = (int)$data[7]; 
                $summerHouseDto = new SummerHouseDto(
                    id: $id, 
                    area: $area, 
                    address: $address, 
                    price: $price, 
                    bedrooms: $bedrooms, 
                    distanceToSea: $distanceToSea, 
                    hasShower: $hasShower, 
                    hasBathroom: $hasBathroom
                ); 
                break; 
            }
        }

        return $summerHouseDto;
    }

    public function create($entity) {
        $this->addRawDataToCsv($entity->toArray());
    }

    public function delete(int $id): bool {
        return $this->deleteRowById($id); 
    }
}