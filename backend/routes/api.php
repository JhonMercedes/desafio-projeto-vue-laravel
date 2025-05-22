<?php

use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);

use Illuminate\Http\Request;

// Route::get('/teste', function () {
//     return response()->json(['message' => 'Rota de teste funcionando!']);
// });

// Route::get('products', [ProductController::class, 'index']);       // Listar todos
// Route::post('products', [ProductController::class, 'store']);      // Criar novo
// Route::get('products/{id}', [ProductController::class, 'show']);   // Detalhar um
// Route::put('products/{id}', [ProductController::class, 'update']); // Atualizar
// Route::delete('products/{id}', [ProductController::class, 'destroy']);
