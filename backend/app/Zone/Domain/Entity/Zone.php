<?php

namespace App\Zone\Domain\Entity;

use App\Zone\Domain\ValueObject\ZoneName;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class Zone
{
    private function __construct(
        private Uuid $uuid,
        private Uuid $restaurantUuid,
        private ZoneName $name,
        private bool $active,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt,
    ) {}

    public static function dddCreate(
        Uuid $uuid,
        Uuid $restaurantUuid,
        string $name,
    ): self {
        return new self(
            uuid: $uuid,
            restaurantUuid: $restaurantUuid,
            name: ZoneName::create($name),
            active: true,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable(),
        );
    }

    public static function fromPersistence(
        string $uuid,
        string $restaurantUuid,
        string $name,
        bool $active,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            uuid: Uuid::create($uuid),
            restaurantUuid: Uuid::create($restaurantUuid),
            name: ZoneName::create($name),
            active: $active,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

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
        return $this->name->value();
    }

    public function active(): bool
    {
        return $this->active;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateName(string $name): void
    {
        $this->name = ZoneName::create($name);
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toggleActive(): void
    {
        $this->active = !$this->active;
        $this->updatedAt = new DateTimeImmutable();
    }
}