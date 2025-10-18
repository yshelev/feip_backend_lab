<?php 

use App\Dto\SummerHouseDto;

class CsvSummerHouseRepository extends AbstractCsvRepository
{  
    public function __construct(string $filepath) {
        $this->filepath = $filepath;
    }

    public function findAll(): array {
        $requests = []; 

        $raw_data = $this->_get_csv_data(); 

        foreach($raw_data as $data) {
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
        $raw_data = $this->_get_csv_data();

        foreach($raw_data as $data) {
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
}