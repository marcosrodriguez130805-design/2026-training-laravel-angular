<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Email;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\ValueObject\PasswordHash;
use App\User\Domain\ValueObject\UserName;

class User
{
    private function __construct(
        private Uuid $id,
        private Uuid $uuid,
        private int $restaurantId,
        private string $role,
        private UserName $name,
        private Email $email,
        private PasswordHash $passwordHash,
        private ?string $pin,
        private ?string $imageSrc,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(int $restaurantId, string $role, Email $email, UserName $name, PasswordHash $passwordHash, ?string $imageSrc, string $pin): self
    {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            Uuid::generate(),
            $restaurantId,
            $role,
            $name,
            $email,
            $passwordHash,
            $pin,
            $imageSrc,
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $id,
        string $uuid,
        int $restaurantId,
        string $role,
        string $name,
        string $email,
        string $passwordHash,
        ?string $pin,
        ?string $imageSrc,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            Uuid::create($id),
            Uuid::create($uuid),
            (int) $restaurantId,
            $role,
            UserName::create($name),
            Email::create($email),
            PasswordHash::create($passwordHash),
            $pin,
            $imageSrc,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    public function restaurantId(): int
    {
        return $this->restaurantId;
    }

        public function role(): string
    {
        return $this->role;
    }

        public function name(): string
    {
        return $this->name->value();
    }

        public function email(): Email
    {
        return $this->email;
    }

        public function passwordHash(): string
    {
        return $this->passwordHash->value();
    }

        public function pin(): ?string
    {
        return $this->pin;
    }

        public function imageSrc(): ?string
    {
        return $this->imageSrc;
    }

        public function createdAt(): DomainDateTime
    {
        return $this->createdAt;
    }

        public function updatedAt(): DomainDateTime
    {
        return $this->updatedAt;
    }

        public function deletedAt(): ?DomainDateTime
    {
        return $this->deletedAt;
    }
}
