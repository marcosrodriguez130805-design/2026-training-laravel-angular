<?php

namespace App\Product\Domain\ValueObject;

class ProductName
{
    private function __construct(
        private string $value
    ) {}

    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Product name cannot be empty');
        }

        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Product name cannot exceed 255 characters');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}