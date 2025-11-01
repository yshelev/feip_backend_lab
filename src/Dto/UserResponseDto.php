<?php

declare(strict_types=1);

namespace App\Dto;

final class UserResponseDto
{
    public function __construct(
        public readonly string $phoneNumber,
    ) {
    }
}
