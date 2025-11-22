<?php

declare(strict_types=1);

namespace App\Dto;

class RequestDto
{
    public function __construct(
        public int $id,
        public int $houseId ,
        public string $phoneNumber,
        public string $comment,
    ) {}
    
    public function toArray(): array {
        return array(
            $this->id, 
            $this->houseId, 
            $this->phoneNumber, 
            $this->comment
        ); 
    }
}