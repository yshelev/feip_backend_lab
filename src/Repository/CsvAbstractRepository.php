<?php
namespace App\Repository;

abstract class CsvAbstractRepository
{
    public function __construct(protected string $filepath) {}

    protected function getCsvData(): array {
        $csvData = []; 
        $handle = fopen($this->filepath, 'r', );

        while (($data = fgetcsv($handle, escape: '\\', separator: ';')) !== false) {
            if ($data !== null) {
                $csvData[] = $data; 
            }
        }

        fclose($handle); 

        return $csvData; 
    }

    protected function addRawDataToCsv(array $data): void {
        $handle = fopen($this->filepath, "a");

        fputcsv($handle, $data, ";"); 

        fclose($handle);
        return; 
    }

    protected function deleteRowById(int $id): bool {
        $currentData = $this->getCsvData(); 

        $flag = false; 

        $handle = fopen($this->filepath, "w"); 

        foreach($currentData as $rowData) {
            if ($rowData[0] != $id) {
                $flag = true; 
                fputcsv($handle, $rowData, ";"); 
            }
        }

        return $flag; 
    }

    abstract public function findAll(): array;
    abstract public function find(int $id);
    abstract public function create($data); 
    abstract public function delete(int $id): bool; 
}