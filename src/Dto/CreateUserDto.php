<?php

namespace App\Dto; 

class CreateUserDto
{
    public function __construct(
        public string $phoneNumber
    ){}
}   