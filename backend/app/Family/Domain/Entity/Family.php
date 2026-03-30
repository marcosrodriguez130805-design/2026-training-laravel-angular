<?php

namespace App\Family\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use App\Family\Domain\ValueObject\FamilyName;

class Family
{
    public function __construct(
        private Uuid $uuid,
        private int $restaurantId,          // ← volvemos a int
        private FamilyName $name,
        private bool $active,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(int $restaurantId, string $name, bool $active): self
    {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            $restaurantId,                 // ← int
            FamilyName::create($name),
            $active,
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $uuid,
        int $restaurantId,              // ← int
        string $name,
        bool $active,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($uuid),
            $restaurantId,                // ← int
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