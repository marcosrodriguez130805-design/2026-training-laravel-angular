<?php

namespace App\Product\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;

class Product
{
    public function __construct(
        private Uuid $uuid,
        private int $restaurantId,
        private int $familyId,
        private int $taxId,
        private string $name,
        private int $price,
        private int $stock,
        private bool $active,
        private ?string $imageSrc,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(
        int $restaurantId,
        int $familyId,
        int $taxId,
        string $name,
        int $price,
        int $stock,
        bool $active,
        ?string $imageSrc = null
    ): self {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            $restaurantId,
            $familyId,
            $taxId,
            $name,
            $price,
            $stock,
            $active,
            $imageSrc,
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $uuid,
        int $restaurantId,
        int $familyId,
        int $taxId,
        string $name,
        int $price,
        int $stock,
        bool $active,
        ?string $imageSrc,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($uuid),
            $restaurantId,
            $familyId,
            $taxId,
            $name,
            $price,
            $stock,
            $active,
            $imageSrc,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    // --- Getters ---
    public function uuid(): Uuid 
    { 
        return $this->uuid; 
    }
    
    public function restaurantId(): int 
    { 
        return $this->restaurantId; 
    }
    
    public function familyId(): int 
    { 
        return $this->familyId; 
    }
    
    public function taxId(): int 
    { 
        return $this->taxId;
    }
   
    public function name(): string 
    { 
        return $this->name; 
    }
    
    public function price(): int 
    { 
        return $this->price; 
    }
   
    public function stock(): int 
    { 
        return $this->stock; 
    }
    
    public function active(): bool 
    { 
        return $this->active; 
    }
    
    public function imageSrc(): ?string 
    { 
        return $this->imageSrc; 
    }
    
    public function createdAt(): DomainDateTime 
    { 
        return $this->createdAt;
    }
    
    public function updatedAt(): DomainDateTime 
    { 
        return $this->updatedAt; 
    }
}