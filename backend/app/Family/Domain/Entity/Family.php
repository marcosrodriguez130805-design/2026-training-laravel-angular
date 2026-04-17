<?php

namespace App\Family\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use App\Family\Domain\ValueObject\FamilyName;

class Family
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantId,
        private FamilyName $name,
        private bool $active,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    /**
     * Método estático para crear una nueva instancia desde el Caso de Uso.
     * Ahora acepta todos los parámetros necesarios para evitar errores de tipos.
     */
    public static function dddCreate(
        Uuid $uuid,
        Uuid $restaurantId,
        string $name,
        bool $active,
        DomainDateTime $createdAt,
        DomainDateTime $updatedAt
    ): self {
        return new self(
            $uuid,
            $restaurantId,
            FamilyName::create($name),
            $active,
            $createdAt,
            $updatedAt,
        );
    }

    /**
     * Método para reconstruir la entidad desde la persistencia (Base de Datos).
     */
    public static function fromPersistence(
        string $uuid,
        string $restaurantId,
        string $name,
        bool $active,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($uuid),
            Uuid::create($restaurantId),
            FamilyName::create($name),
            $active,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    // --- Getters & Business Logic ---

    public function updateName(string $name): void
    {
        $this->name = FamilyName::create($name);
    }

    public function toggleActive(): void
    {
        $this->active = !$this->active;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function restaurantId(): Uuid
    {
        return $this->restaurantId;
    }

    public function name(): string
    {
        return $this->name->value();
    }

    public function active(): bool
    {
        return $this->active;
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