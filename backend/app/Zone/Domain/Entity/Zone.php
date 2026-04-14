<?php

namespace App\Zone\Domain\Entity;

use App\Shared\Domain\ValueObject\Uuid;

final class Zone
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantUuid,
        private string $name
    ) {}

    public static function dddCreate(Uuid $restaurantUuid, string $name): self
    {
        return new self(
            Uuid::generate(), // 👈 CAMBIADO: Antes decía random()
            $restaurantUuid,
            $name
        );
    }

    // Getters
    public function uuid(): Uuid 
    { 
        return $this->uuid; 
    }
    
    public function restaurantUuid(): Uuid 
    { 
        return $this->restaurantUuid; 
    }
    
    public function name(): string 
    { 
        return $this->name; 
    }

    public function update(string $name): void
    {
        $this->name = $name;
    }
}