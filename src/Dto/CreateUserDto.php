<?php

declare(strict_types=1);

namespace App\Dto;

final class CreateUserDto
{
    public function __construct(
        public string $phoneNumber
    ) {
    }
}
