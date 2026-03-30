<?php

namespace App\Family\Infrastructure\Entrypoint\Http;

use App\Family\Application\ListFamilies\ListFamilies;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListFamiliesController
{
    public function __invoke(Request $request, ListFamilies $listFamilies): JsonResponse
    {
        $onlyActive = $request->query('active', false);

        $responses = $listFamilies($onlyActive);

        return response()->json(
            array_map(fn($response) => $response->toArray(), $responses), 200
        );
    }
}

