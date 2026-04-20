<?php

namespace App\Table\Application\ListTables;

use App\Table\Domain\Entity\Table; // Importante

class ListTablesResponse
{
    public function __construct(
        private Table $table // Al poner el tipo 'Table', te asegurarás de que no entre un array
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