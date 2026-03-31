<?php

use App\Family\Infrastructure\Entrypoint\Http\CreateFamilyController;
use App\Family\Infrastructure\Entrypoint\Http\DeleteFamilyController;
use App\Family\Infrastructure\Entrypoint\Http\GetFamilyController;
use App\Family\Infrastructure\Entrypoint\Http\ListFamiliesController;
use App\Family\Infrastructure\Entrypoint\Http\ToggleFamilyActiveController;
use App\Family\Infrastructure\Entrypoint\Http\UpdateFamilyController;
use App\User\Infrastructure\Entrypoint\Http\CreateUserController;
use App\User\Infrastructure\Entrypoint\Http\LoginUserController;
use App\User\Infrastructure\Entrypoint\Http\PostController;
use Illuminate\Support\Facades\Route;

Route::post('/users', CreateUserController::class);

// Family routes
Route::get('/families', ListFamiliesController::class);
Route::post('/families', CreateFamilyController::class);
Route::get('/families/{uuid}', GetFamilyController::class);
Route::put('/families/{uuid}', UpdateFamilyController::class);
Route::delete('/families/{uuid}', DeleteFamilyController::class);
Route::patch(
    '/families/{uuid}/toggle-active',
    [ToggleFamilyActiveController::class, '__invoke']
);
Route::post('login', LoginUserController::class);