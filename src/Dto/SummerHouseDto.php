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
        public int $distanceToSea,
        public bool $hasShower,
        public bool $hasBathroom,
    ) {}

    public function toArray(): array {
        return array(
            $this->id, 
            $this->area,
            $this->address,
            $this->price,
            $this->bedrooms,
            $this->distanceToSea,
            $this->hasShower,
            $this->hasBathroom,
        );
    }
}