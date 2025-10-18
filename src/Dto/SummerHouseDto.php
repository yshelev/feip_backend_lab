<?php

declare(strict_types=1);

namespace App\Dto;

class SummerHouseDto
{
    public function __construct(
        public int $id,
        public float $area,
        public string $address,
        public int $price,
        public int $bedrooms,
        public int $distance_to_sea,
        public bool $has_shower,
        public bool $has_bathroom,
    ) {}

    public function to_array(): array {
        return array(
            $this->id, 
            $this->area,
            $this->address,
            $this->price,
            $this->bedrooms,
            $this->distance_to_sea,
            $this->has_shower,
            $this->has_bathroom,
        );
    }
}