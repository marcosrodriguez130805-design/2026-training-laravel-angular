<?php

namespace App\Zone\Domain\Interfaces;

use App\Zone\Domain\Entity\Zone;

interface ZoneRepositoryInterface
{
    public function save(Zone $zone): void;

    // Ya que estamos, dejamos definida la búsqueda por si la necesitas luego
    public function findByUuid(string $uuid): ?Zone;
    
    // Y para el listado que querrás hacer pronto
    public function listZones(string $restaurantUuid): array;

    public function update(Zone $zone): void;

    public function delete(string $uuid): void;
}