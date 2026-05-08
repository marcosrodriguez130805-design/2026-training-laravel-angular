<?php

declare(strict_types=1);

namespace App\Family\Domain\Exception;

use Exception;

final class FamilyAlreadyExistsException extends Exception
{
    public function __construct(string $name)
    {
        parent::__construct("La familia con el nombre '{$name}' ya existe.");
    }
}