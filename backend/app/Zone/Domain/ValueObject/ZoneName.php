<?php

namespace App\Zone\Domain\ValueObject;

class ZoneName
{
    private string $value;

    private function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        return new self($value);
    }

    private function validate(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Zone name cannot be empty');
        }

        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Zone name cannot exceed 255 characters');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}