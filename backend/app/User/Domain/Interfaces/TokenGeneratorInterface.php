<?php

namespace App\User\Domain\Interfaces;

use App\User\Domain\Entity\User;

interface TokenGeneratorInterface
{
    /**
     * Genera un token de acceso para la entidad de usuario.
     */
    public function generate(User $user): string;
}