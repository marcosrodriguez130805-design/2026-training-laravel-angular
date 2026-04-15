<?php

namespace App\Family\Application\ListFamilies;

use App\Family\Domain\Interfaces\FamilyRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;

class ListFamilies
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    ) {}
    
    // Añadimos el restaurantUuid como string (luego lo validamos con el VO)
    public function __invoke(string $restaurantUuid, bool $onlyActive = false): array
    {
        // Validamos que sea un UUID real antes de ir a base de datos
        $uuid = Uuid::create($restaurantUuid);

        // Le pasamos el filtro al repositorio
        $families = $this->repository->findAll($uuid->value(), $onlyActive);
        
        return array_map(
            function($family) {
                // Asegúrate de que ListFamiliesResponse devuelva un array al final
                return (new ListFamiliesResponse($family))->toArray();
            },
            $families
        );
    }
}