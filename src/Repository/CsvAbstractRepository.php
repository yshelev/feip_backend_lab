<?php
namespace App\Repository;

abstract class CsvAbstractRepository
{
    public function __construct(protected string $filepath) {}

    protected function _get_csv_data(): array {
        $csv_data = []; 
        $handle = fopen($this->filepath, 'r', );

        while (($data = fgetcsv($handle, escape: '\\', separator: ';')) !== false) {
            if ($data !== null) {
                $csv_data[] = $data; 
            }
        }

        fclose($handle); 

        return $csv_data; 
    }

    protected function _add_raw_data_to_csv(array $data): void {
        $handle = fopen($this->filepath, "a");

        fputcsv($handle, $data, ";"); 

        fclose($handle);
        return; 
    }

    protected function _delete_row_by_id(int $id): bool {
        $current_data = $this->_get_csv_data(); 

        $flag = false; 

        $handle = fopen($this->filepath, "w"); 

        foreach($current_data as $row_data) {
            if ($row_data[0] != $id) {
                $flag = true; 
                fputcsv($handle, $row_data, ";"); 
            }
        }

        return $flag; 
    }

    abstract public function findAll(): array;
    abstract public function find(int $id);
    abstract public function create($data); 
    abstract public function delete(int $id): bool; 
}