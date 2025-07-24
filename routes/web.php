<?php

use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin'); //redireccionar al login


Route::redirect('/admin', '/admin/inicio'); //ruta para el dashboard perzonalizado


//ventas
Route::get('/buscar-productos', [SaleController::class, 'buscarProductoVenta'])->name('buscar.productos'); //ruta buscar productos
Route::post('/venta-registrada', [SaleController::class, 'registrarVenta'])->name('registrar.venta'); //ruta registrar venta (controlador)
//

