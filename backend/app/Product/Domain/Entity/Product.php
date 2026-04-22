<?php

namespace App\Product\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use App\Product\Domain\ValueObject\ProductName;
use App\Product\Domain\ValueObject\ProductPrice;
use App\Product\Domain\ValueObject\ProductStock;

class Product
{
    public function __construct(
        private Uuid $uuid,
        private Uuid $restaurantId,
        private Uuid $familyId,
        private Uuid $taxId,
        private ProductName $name,
        private ProductPrice $price,
        private ProductStock $stock,
        private bool $active,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
        private ?string $imageSrc = null
    ) {}

    public static function dddCreate(
        Uuid $uuid,
        Uuid $restaurantId,
        Uuid $familyId,
        Uuid $taxId,
        string $name,
        int $price,
        int $stock,
        bool $active,
        DomainDateTime $createdAt,
        DomainDateTime $updatedAt,
        ?string $imageSrc = null
    ): self {
        return new self(
            $uuid,
            $restaurantId,
            $familyId,
            $taxId,
            ProductName::create($name),
            ProductPrice::create($price),
            ProductStock::create($stock),
            $active,
            $createdAt,
            $updatedAt,
            $imageSrc
        );
    }

    public static function fromPersistence(
        string $uuid,
        string $restaurantId,
        string $familyId,
        string $taxId,
        string $name,
        int $price,
        int $stock,
        bool $active,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        ?string $imageSrc = null
    ): self {
        return new self(
            Uuid::create($uuid),
            Uuid::create($restaurantId),
            Uuid::create($familyId),
            Uuid::create($taxId),
            ProductName::create($name),
            ProductPrice::create($price),
            ProductStock::create($stock),
            $active,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
            $imageSrc
        );
    }

    // --- Getters ---
    public function uuid(): Uuid { return $this->uuid; }
    public function restaurantId(): Uuid { return $this->restaurantId; }
    public function familyId(): Uuid { return $this->familyId; }
    public function taxId(): Uuid { return $this->taxId; }
    public function name(): string { return $this->name->value(); }
    public function price(): int { return $this->price->value(); }
    public function stock(): int { return $this->stock->value(); }
    public function active(): bool { return $this->active; }
    public function imageSrc(): ?string { return $this->imageSrc; }
    public function createdAt(): DomainDateTime { return $this->createdAt; }
    public function updatedAt(): DomainDateTime { return $this->updatedAt; }

    public function update(
        Uuid $familyId,
        Uuid $taxId,
        string $name,
        int $price,
        int $stock,
        ?string $imageSrc
    ): void {
        $this->familyId = $familyId;
        $this->taxId = $taxId;
        $this->name = ProductName::create($name);
        $this->price = ProductPrice::create($price);
        $this->stock = ProductStock::create($stock);
        $this->imageSrc = $imageSrc;
        $this->updatedAt = DomainDateTime::now();
    }

    public function toggleActive(): void
    {
        $this->active = !$this->active;
        $this->updatedAt = DomainDateTime::now();
    }
}