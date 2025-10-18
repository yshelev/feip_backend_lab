<?php
abstract class AbstractCsvRepository
{
    protected string $filepath;

    protected function _get_csv_data(): array {
        $csv_data = []; 
        $file = fopen($this->filepath, 'r');

        while (($data = fgetcsv($file, escape: '\\')) !== false) {
            if ($data !== null) {
                $csv_data[] = $data; 
            }
        }

        return $csv_data; 
    }
    abstract public function findAll(): array;
    abstract public function find(int $id);
}