<?php

namespace App\User\Domain\ValueObject;

class UserRole
{
    private const VALID_ROLES = ['admin', 'manager', 'waiter'];

    private function __construct(
        private string $value
    ) {}

    public static function create(string $value): self
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('User role cannot be empty');
        }

        if (!in_array($value, self::VALID_ROLES)) {
            throw new \InvalidArgumentException('Invalid role: ' . $value . '. Valid roles are: ' . implode(', ', self::VALID_ROLES));
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}