<?php

namespace App\Zone\Domain\Interfaces;

use App\Shared\Domain\ValueObject\Uuid;
use App\Zone\Domain\Entity\Zone;

interface ZoneRepositoryInterface
{
    public function save(Zone $zone): void;

    // Ya que estamos, dejamos definida la búsqueda por si la necesitas luego
    public function findByUuid(Uuid $uuid, string $restaurantUuid): ?Zone;
    
    // Y para el listado que querrás hacer pronto
    public function listZones(string $restaurantUuid): array;

    public function update(Zone $zone): void;

    public function delete(string $uuid): void;
}