<?php

namespace App\Product\Application\CreateProduct;

use App\Product\Domain\Entity\Product;

class CreateProductResponse
{
    public function __construct(
        private Product $product
    ) {}

    public function toArray(): array
    {
        return [
            'uuid'          => $this->product->uuid()->value(),
            'restaurant_id' => $this->product->restaurantId(),
            'family_id'     => $this->product->familyId(),
            'tax_id'        => $this->product->taxId(),
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