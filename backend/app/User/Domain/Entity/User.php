<?php

namespace App\User\Domain\Entity;

use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use App\User\Domain\ValueObject\PasswordHash;
use App\User\Domain\ValueObject\UserName;
use App\User\Domain\ValueObject\UserRole;
use App\User\Domain\ValueObject\Pin;

final class User
{
    private function __construct(
        private Uuid $uuid,
        private Uuid $restaurantUuid,
        private UserRole $role,
        private UserName $name,
        private string $email,
        private PasswordHash $passwordHash,
        private ?Pin $pin,
        private ?string $imageSrc,
        private DomainDateTime $createdAt,
        private DomainDateTime $updatedAt,
    ) {}

    public static function dddCreate(Uuid $restaurantUuid, string $role, string $email, UserName $name, PasswordHash $passwordHash, ?string $imageSrc, ?string $pin): self
    {
        $now = DomainDateTime::now();

        return new self(
            Uuid::generate(),
            $restaurantUuid,
            UserRole::create($role),
            $name,
            $email,
            $passwordHash,
            $pin ? Pin::create($pin) : null,
            $imageSrc,
            $now,
            $now,
        );
    }

    public static function fromPersistence(
        string $uuid,
        string $restaurantUuid,
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
            Uuid::create($uuid),
            Uuid::create($restaurantUuid),
            UserRole::create($role),
            UserName::create($name),
            $email,
            PasswordHash::create($passwordHash),
            $pin ? Pin::create($pin) : null,
            $imageSrc,
            DomainDateTime::create($createdAt),
            DomainDateTime::create($updatedAt),
        );
    }

    public function updateName(UserName $name): void { $this->name = $name; }
    public function updateEmail(string $email): void { $this->email = $email; }
    public function updatePassword(PasswordHash $password): void { $this->passwordHash = $password; }
    public function updatePin(?string $pin): void { $this->pin = $pin ? Pin::create($pin) : null; }
    public function updateRole(string $role): void { $this->role = UserRole::create($role); }
    public function updateImageSrc(?string $imageSrc): void { $this->imageSrc = $imageSrc; }
    public function updateRestaurantUuid(Uuid $restaurantUuid): void { $this->restaurantUuid = $restaurantUuid; }

    public function id(): Uuid { return $this->uuid; }
    public function uuid(): Uuid { return $this->uuid; }
    public function restaurantUuid(): Uuid { return $this->restaurantUuid; }
    public function role(): string { return $this->role->value(); }
    public function name(): string { return $this->name->value(); }
    public function email(): string { return $this->email; }
    public function passwordHash(): string { return $this->passwordHash->value(); }
    public function pin(): ?string { return $this->pin?->value(); }
    public function imageSrc(): ?string { return $this->imageSrc; }
    public function createdAt(): DomainDateTime { return $this->createdAt; }
    public function updatedAt(): DomainDateTime { return $this->updatedAt; }
}