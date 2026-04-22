<?php

namespace App\User\Domain\ValueObject;

class Pin
{
    private function __construct(
        private string $value
    ) {}

    public static function create(string $value): self
    {
        if (!preg_match('/^\d{4}$/', $value)) {
            throw new \InvalidArgumentException('Pin must be exactly 4 digits');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}