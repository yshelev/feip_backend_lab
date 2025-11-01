<?php

declare(strict_types=1);

namespace App\Dto;

final class CreateRequestDto
{
    public function __construct(
        public int $houseId,
        public string $phoneNumber,
        public string $comment,
    ) {
    }
}
