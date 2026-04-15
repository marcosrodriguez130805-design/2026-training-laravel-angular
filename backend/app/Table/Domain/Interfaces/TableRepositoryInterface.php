<?php

namespace App\Table\Domain\Interfaces;

use App\Table\Domain\Entity\Table;
use App\Shared\Domain\ValueObject\Uuid;

interface TableRepositoryInterface
{
    public function save(Table $table): void;
    
    public function findByUuid(string $uuid): ?Table;
    
    public function listByZone(Uuid $zoneUuid): array;
    
    public function update(Table $table): void;
    
    public function delete(string $uuid): void;
}