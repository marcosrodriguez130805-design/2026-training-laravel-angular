<?php

namespace App\Tax\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;

class Tax
{
    public function __construct(
        private Uuid $uuid,
        private int $restaurantId,
        private string $name,
        private int $percentage,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(int $restaurantId, string $name, int $percentage): self
    {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            $restaurantId,
            $name,
            $percentage,
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $uuid,
        int $restaurantId,
        string $name,
        int $percentage,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($uuid),
            $restaurantId,
            $name,
            $percentage,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    // Getters
    public function uuid(): Uuid 
    { 
        return $this->uuid; 
    }
    
    public function restaurantId(): int 
    { 
        return $this->restaurantId; 
    }
    
    public function name(): string 
    { 
        return $this->name; 
    }
    
    public function percentage(): int 
    { 
        return $this->percentage; 
    }
    
    public function createdAt(): DomainDateTime 
    { 
        return $this->createdAt; 
    }
    
    public function updatedAt(): DomainDateTime 
    { 
        return $this->updatedAt; 
    }

    public function update(string $name, int $percentage): void
    {
        $this->name = $name;
        $this->percentage = $percentage;
        $this->updatedAt = DomainDateTime::now();
    }
}