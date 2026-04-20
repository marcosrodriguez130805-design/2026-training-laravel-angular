<?php

namespace App\Table\Domain\Interfaces;

use App\Table\Domain\Entity\Table;
use App\Shared\Domain\ValueObject\Uuid;

interface TableRepositoryInterface
{
    public function save(Table $table): void;
    
    public function findByUuid(string $uuid): ?Table;
    
    public function findByZone(string $restaurantUuid, string $zoneUuid): array;

    public function listByRestaurant(Uuid $restaurantUuid): array;
    
    public function update(Table $table): void;
    
    public function delete(string $uuid): void;
}