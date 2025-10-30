<?php

namespace App\Dto; 

class UserResponseDto 
{
    public function __construct(
        public readonly string $phoneNumber, 
    ) {}
}