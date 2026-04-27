<?php

use App\Family\Infrastructure\Entrypoint\Http\CreateFamilyController;
use App\Family\Infrastructure\Entrypoint\Http\DeleteFamilyController;
use App\Family\Infrastructure\Entrypoint\Http\GetFamilyController;
use App\Family\Infrastructure\Entrypoint\Http\ListFamiliesController;
use App\Family\Infrastructure\Entrypoint\Http\ToggleFamilyActiveController;
use App\Family\Infrastructure\Entrypoint\Http\UpdateFamilyController;
use App\User\Infrastructure\Entrypoint\Http\CreateUserController;
use App\User\Infrastructure\Entrypoint\Http\LoginUserController;
use App\User\Infrastructure\Entrypoint\Http\GetUserUuidController;
use App\User\Infrastructure\Entrypoint\Http\GetUserEmailController;
use App\User\Infrastructure\Entrypoint\Http\UpdateUserController;
use App\User\Infrastructure\Entrypoint\Http\DeleteUserController;
use App\User\Infrastructure\Entrypoint\Http\ListUsersController;
use App\Tax\Infrastructure\Entrypoint\Http\CreateTaxController;
use App\Tax\Infrastructure\Entrypoint\Http\ListTaxesController;
use App\Tax\Infrastructure\Entrypoint\Http\GetTaxController;
use App\Tax\Infrastructure\Entrypoint\Http\UpdateTaxController;
use App\Tax\Infrastructure\Entrypoint\Http\DeleteTaxController;
use App\Product\Infrastructure\Entrypoint\Http\CreateProductController;
use App\Product\Infrastructure\Entrypoint\Http\ListProductsController;
use App\Product\Infrastructure\Entrypoint\Http\GetProductController;
use App\Product\Infrastructure\Entrypoint\Http\ToggleProductActiveController;
use App\Product\Infrastructure\Entrypoint\Http\UpdateProductController;
use App\Product\Infrastructure\Entrypoint\Http\DeleteProductController;
use App\Zone\Infrastructure\Entrypoint\Http\CreateZoneController;
use App\Zone\Infrastructure\Entrypoint\Http\ListZonesController;
use App\Zone\Infrastructure\Entrypoint\Http\GetZoneController;
use App\Zone\Infrastructure\Entrypoint\Http\UpdateZoneController;
use App\Zone\Infrastructure\Entrypoint\Http\DeleteZoneController;
use App\Zone\Infrastructure\Entrypoint\Http\ToggleZoneActiveController;
use App\Table\Infrastructure\Entrypoint\Http\CreateTableController;
use App\Table\Infrastructure\Entrypoint\Http\ListTablesController;
use App\Table\Infrastructure\Entrypoint\Http\ListTablesByZoneController;
use App\Table\Infrastructure\Entrypoint\Http\GetTableController;
use App\Table\Infrastructure\Entrypoint\Http\UpdateTableController;
use App\Table\Infrastructure\Entrypoint\Http\DeleteTableController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/users', CreateUserController::class);
Route::post('/login', LoginUserController::class);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    // Users
    Route::get('/users', ListUsersController::class);
    Route::get('/users/{uuid}', GetUserUuidController::class);
    Route::get('/users/email/{email}', GetUserEmailController::class);
    Route::put('/users/{uuid}', UpdateUserController::class);
    Route::delete('/users/{uuid}', DeleteUserController::class);

    // Families
    Route::get('/families', ListFamiliesController::class);
    Route::post('/families', CreateFamilyController::class);
    Route::get('/families/{uuid}', GetFamilyController::class);
    Route::put('/families/{uuid}', UpdateFamilyController::class);
    Route::delete('/families/{uuid}', DeleteFamilyController::class);
    Route::patch('/families/{uuid}/toggle-active', ToggleFamilyActiveController::class, '__invoke');

    // Taxes
    Route::post('/taxes', CreateTaxController::class);
    Route::get('/taxes', ListTaxesController::class);
    Route::get('/taxes/{uuid}', GetTaxController::class);
    Route::put('/taxes/{uuid}', UpdateTaxController::class);
    Route::delete('/taxes/{uuid}', DeleteTaxController::class);

    // Products
    Route::post('/products', CreateProductController::class);
    Route::get('/products', ListProductsController::class);
    Route::get('/products/{uuid}', GetProductController::class);
    Route::patch('/products/{uuid}/toggle-active', ToggleProductActiveController::class);
    Route::put('/products/{uuid}', UpdateProductController::class);
    Route::delete('/products/{uuid}', DeleteProductController::class);

    // Zones
    Route::post('/zones', CreateZoneController::class);
    Route::get('/zones', ListZonesController::class);
    Route::get('/zones/{uuid}', GetZoneController::class);
    Route::put('/zones/{uuid}', UpdateZoneController::class);
    Route::patch('/zones/{uuid}/toggle-active', ToggleZoneActiveController::class);
    Route::delete('/zones/{uuid}', DeleteZoneController::class);

    // Tables
    Route::post('/tables', CreateTableController::class);
    Route::get('/tables', ListTablesController::class);
    Route::get('/tables/zones/{uuid_zone}', ListTablesByZoneController::class);
    Route::get('/tables/{uuid}', GetTableController::class);
    Route::put('/tables/{uuid}', UpdateTableController::class);
    Route::delete('/tables/{uuid}', DeleteTableController::class);
});