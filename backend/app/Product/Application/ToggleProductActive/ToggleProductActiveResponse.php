<?php

namespace App\Product\Application\ToggleProductActive;

use App\Product\Domain\Entity\Product;

class ToggleProductActiveResponse
{
    public function __construct(
        private Product $product,
    ) {}

    public function toArray(): array
    {
        return [
            'uuid'          => $this->product->uuid()->value(),
            'restaurant_uuid' => $this->product->restaurantId()->value(),
            'family_uuid'     => $this->product->familyId()->value(),
            'tax_uuid'        => $this->product->taxId()->value(),
            'name'          => $this->product->name(),
            'price'         => $this->product->price(),
            'stock'         => $this->product->stock(),
            'active'        => $this->product->active(),
            'image_src'     => $this->product->imageSrc(),
            'created_at'    => $this->product->createdAt()->value()->format('Y-m-d H:i:s'),
            'updated_at'    => $this->product->updatedAt()->value()->format('Y-m-d H:i:s'),
        ];
    }
}