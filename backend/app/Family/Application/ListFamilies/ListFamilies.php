<?php


namespace App\Family\Application\ListFamilies;

use App\Family\Domain\Interfaces\FamilyRespositoryInterface;

class ListFamilies
{
    public function __construct(
        private FamilyRepositoryInterface $repository,
    
    ) {}
    
    public function __invoke(bool $onlyActive = false): array
    {
        $families = $this->repository->findAll($onlyActive);
        
        return array_map(
            function($family) {
            return new ListFamiliesResponse($family);
            },
            $families
        );
    }
}