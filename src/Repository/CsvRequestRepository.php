<?php
namespace App\Repository;

use App\Dto\RequestDto;

class CsvRequestRepository extends CsvAbstractRepository
{  
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

    public function create($entity) {
        $this->_add_raw_data_to_csv($entity->to_array());
    }

    public function delete(int $id): bool {
        return $this->_delete_row_by_id($id); 
    }
}