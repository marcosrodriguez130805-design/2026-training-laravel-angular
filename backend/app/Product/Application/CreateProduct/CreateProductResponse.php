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
            'restaurant_uuid' => $this->product->restaurantId()->value(),
            'family_uuid'     => $this->product->familyId()->value(),
            'tax_uuid'        => $this->product->taxId()->value(),
            'name'          => $this->product->name(),
            'price'         => $this->product->price(),
            'stock'         => $this->product->stock(),
            'active'        => $this->product->active(),
            'image_src'     => $this->product->imageSrc(),
        ];
    }
}