<?php

declare(strict_types=1);

namespace App\Dto;

final class UpdateRequestDto
{
    public function __construct(
        public int $id,
        public int $houseId,
        public string $phoneNumber,
        public string $comment,
    ) {
    }
}
