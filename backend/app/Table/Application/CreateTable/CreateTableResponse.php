<?php

namespace App\Table\Application\CreateTable;

use App\Table\Domain\Entity\Table;

final class CreateTableResponse
{
    public function __construct(
        private Table $table
    ) {}

    public function toArray(): array
    {
        return [
            'uuid'            => $this->table->uuid()->value(),
            'restaurant_uuid' => $this->table->restaurantUuid()->value(),
            'zone_uuid'       => $this->table->zoneUuid()->value(),
            'name'            => $this->table->name(),
        ];
    }
}