<?php

namespace App\Table\Domain\Entity;

use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Table\Domain\ValueObject\TableName;

final class Table
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantUuid,
        private Uuid $zoneUuid,
        private TableName $name,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

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
            TableName::create($name),
            $createdAt,
            $updatedAt
        );
    }

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
            TableName::create($name),
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt)
        );
    }

    // --- Getters ---
    public function uuid(): Uuid { return $this->uuid; }
    public function restaurantUuid(): Uuid { return $this->restaurantUuid; }
    public function zoneUuid(): Uuid { return $this->zoneUuid; }
    public function name(): string { return $this->name->value(); }
    public function createdAt(): DomainDateTime { return $this->createdAt; }
    public function updatedAt(): DomainDateTime { return $this->updatedAt; }

    // --- Business Logic ---
    public function update(string $name): void
    {
        $this->name = TableName::create($name);
    }
}