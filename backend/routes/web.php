<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

// Route::get('/teste', function () {
//     return response()->json(['message' => 'Rota de teste funcionando!']);
// });

Route::get('/', function () {
    return view('welcome');
});
