<?php

namespace App\Product\Domain\ValueObject;

class ProductStock
{
    private function __construct(
        private int $value
    ) {}

    public static function create(int $value): self
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Product stock cannot be negative');
        }

        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }
}