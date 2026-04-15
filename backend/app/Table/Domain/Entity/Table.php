<?php

namespace App\Table\Domain\Entity;

use App\Shared\Domain\ValueObject\Uuid;

final class Table
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantUuid,
        private Uuid $zoneUuid,
        private string $name
    ) {}

    public static function dddCreate(
        Uuid $restaurantUuid, 
        Uuid $zoneUuid, 
        string $name
    ): self {
        return new self(
            Uuid::generate(),
            $restaurantUuid,
            $zoneUuid,
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
    
    public function zoneUuid(): Uuid 
    { 
        return $this->zoneUuid; 
    }
    
    public function name(): string 
    { 
        return $this->name; 
    }

    public function updateName(string $name): void
    {
        $this->name = $name;
    }
}