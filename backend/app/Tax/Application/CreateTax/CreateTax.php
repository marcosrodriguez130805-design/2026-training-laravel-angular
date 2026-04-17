<?php

namespace App\Tax\Application\CreateTax;

use App\Tax\Domain\Entity\Tax;
use App\Tax\Domain\Interfaces\TaxRepositoryInterface;
use App\Shared\Domain\ValueObject\DomainDateTime;
use App\Shared\Domain\ValueObject\Uuid;
use Illuminate\Support\Str; // Para generar el UUID si no tienes el método random()

final class CreateTax
{
    public function __construct(
        private TaxRepositoryInterface $repository
    ) {}

    public function __invoke(string $name, float $percentage, string $restaurantUuid): CreateTaxResponse
    {
        $taxUuid = Uuid::create(Str::uuid()->toString());
    $restaurantId = Uuid::create($restaurantUuid);
    
    // En lugar de new \DateTimeImmutable(), usamos tu Value Object
    // Si tiene un método "now", úsalo. Si no, usa "create" con un string
    $now = DomainDateTime::now(); 
    // O si no existe .now(): DomainDateTime::create(date('Y-m-d H:i:s'));

    $tax = new Tax(
        $taxUuid,
        $restaurantId,
        $name,
        $percentage,
        $now, // Ahora sí es de tipo DomainDateTime
        $now
    );

        $this->repository->save($tax);

        return new CreateTaxResponse($tax);
    }
}