<?php

namespace App\Tax\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tax\Domain\ValueObject\TaxName;
use App\Tax\Domain\ValueObject\TaxPercentage;

class Tax
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantId,
        private TaxName $name,
        private TaxPercentage $percentage,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(Uuid $restaurantId, string $name, int $percentage): self
    {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            $restaurantId,
            TaxName::create($name),
            TaxPercentage::create($percentage),
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $uuid,
        string $restaurantId,
        string $name,
        int $percentage,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($uuid),
            Uuid::create($restaurantId),
            TaxName::create($name),
            TaxPercentage::create($percentage),
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    public function uuid(): Uuid { return $this->uuid; }
    public function restaurantId(): Uuid { return $this->restaurantId; }
    public function name(): string { return $this->name->value(); }
    public function percentage(): int { return $this->percentage->value(); }
    public function createdAt(): DomainDateTime { return $this->createdAt; }
    public function updatedAt(): DomainDateTime { return $this->updatedAt; }

    public function update(string $name, int $percentage): void
    {
        $this->name = TaxName::create($name);
        $this->percentage = TaxPercentage::create($percentage);
        $this->updatedAt = DomainDateTime::now();
    }
}