<?php

declare(strict_types=1);

namespace App\Dto;

final class CreateSummerHouseDto
{
    public function __construct(
        public float $area,
        public string $address,
        public int $price,
        public int $bedrooms,
        public int $distanceToSea,
        public bool $hasShower,
        public bool $hasBathroom,
    ) {
    }
}
