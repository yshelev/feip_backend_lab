<?php

use App\Dto\RequestDto;

class CsvRequestRepository extends AbstractCsvRepository
{  
    public function __construct(string $filepath) {
        $this->filepath = $filepath;
    }

    public function findAll(): array {
        $requests = []; 

        $raw_data = $this->_get_csv_data(); 

        foreach($raw_data as $data) {
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
        $raw_data = $this->_get_csv_data();

        foreach($raw_data as $data) {
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
}