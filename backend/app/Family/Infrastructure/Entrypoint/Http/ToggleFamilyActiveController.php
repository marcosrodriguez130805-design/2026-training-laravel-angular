<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\ToggleFamilyActive\ToggleFamilyActive;
use Illuminate\Http\Request;

class ToggleFamilyActiveController
{
    public function __invoke(Request $request, string $uuid)
    {
        // Obtenemos la clase de aplicación desde el contenedor
        $toggle = app(ToggleFamilyActive::class);

        // Ejecutamos la acción
        $response = $toggle($uuid);

        // Retornamos la respuesta JSON
        return response()->json($response);
    }
}