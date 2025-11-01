<?php

declare(strict_types=1);

namespace App\Dto;

class CreateUserDto
{
    public function __construct(
        public string $phoneNumber
    ) {
    }
}
