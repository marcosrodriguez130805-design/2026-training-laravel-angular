<?php

namespace App\Table\Domain\Entity;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime;

final class Table
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantUuid,
        private Uuid $zoneUuid,
        private string $name,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    /**
     * Método para crear una nueva instancia (Dominio)
     */
    public static function dddCreate(
        Uuid $uuid,
        Uuid $restaurantUuid,
        Uuid $zoneUuid,
        string $name,
        DomainDateTime $createdAt,
        DomainDateTime $updatedAt
    ): self {
        return new self(
            $uuid,
            $restaurantUuid,
            $zoneUuid,
            $name,
            $createdAt,
            $updatedAt
        );
    }

    /**
     * Reconstrucción desde Persistencia (Infraestructura)
     */
    public static function fromPersistence(
        string $uuid,
        string $restaurantUuid,
        string $zoneUuid,
        string $name,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ): self {
        return new self(
            Uuid::create($uuid),
            Uuid::create($restaurantUuid),
            Uuid::create($zoneUuid),
            $name,
            // Usamos .create() para ser consistentes con tu clase Family
            DomainDateTime::create($createdAt), 
            DomainDateTime::create($updatedAt)
        );
    }

    // --- Getters ---

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

    public function createdAt(): DomainDateTime 
    { 
        return $this->createdAt; 
    }

    public function updatedAt(): DomainDateTime 
    { 
        return $this->updatedAt; 
    }

    // --- Business Logic ---

    public function updateName(string $name): void
    {
        $this->name = $name;
    }
}