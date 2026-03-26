<?php

namespace App\Family\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use App\Family\Domain\ValueObject\FamilyName;

class Family
{
    /**
     * Create a new class instance.
     */
    public function __construct(
    
        private Uuid $id,
        private Uuid $uuid,
        private Uuid $restaurantId,
        private FamilyName $name,
        private bool $active,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(Uuid $restaurantId, string $name, bool $active): self
    {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            Uuid::generate(),
            $restaurantId,
            FamilyName::create($name),
            $active,
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $id,
        string $uuid,
        string $restaurantId,
        string $name,
        bool $active,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($id),
            Uuid::create($uuid),
            Uuid::create($restaurantId),
            FamilyName::create($name),
            $active,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    public function updateName(string $name): void
    {
        $this->name = FamilyName::create($name);
    }

    public function toggleActive(): void
    {
        $this->active = !$this->active;
    }

    public function id(): Uuid
    {
        return $this->id;
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

    


