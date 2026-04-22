<?php

namespace App\Table\Domain\ValueObject;

class TableName
{
    private function __construct(
        private string $value
    ) {}

    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Table name cannot be empty');
        }

        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Table name cannot exceed 255 characters');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}