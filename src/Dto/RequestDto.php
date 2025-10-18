<?php

declare(strict_types=1);

namespace App\Dto;

class RequestDto
{
    public function __construct(
        public int $id,
        public int $house_id ,
        public string $phone_number,
        public string $comment,
    ) {}
    
    public function to_array(): array {
        return array(
            $this->id, 
            $this->house_id, 
            $this->phone_number, 
            $this->comment
        ); 
    }
}