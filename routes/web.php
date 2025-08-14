<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SubActividadController;
use App\Models\Partner;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin'); //redireccionar al login


Route::redirect('/admin', '/admin/inicio'); //ruta para el dashboard perzonalizado


//ventas
Route::get('/buscar-productos', [SaleController::class, 'buscarProductoVenta'])->name('buscar.productos'); //ruta buscar productos
Route::post('/venta-registrada', [SaleController::class, 'registrarVenta'])->name('registrar.venta'); //ruta registrar venta (controlador)
//

//Grupo familiar
Route::POST('/detalles-familiares', [PartnerController::class, 'detallesGrupoFamiliar'])->name('detalles.familiares'); //ruta traer integrantes familiares
Route::PATCH('/detalles-familiares/eliminar-integrante', [PartnerController::class, 'eliminarIntegrante'])->name('eliminar.integrante'); //ruta eliminar integrante
Route::POST('/detalles-familiares/buscar-integrante', [PartnerController::class, 'buscarIntegrante'])->name('buscar.integrante'); //ruta buscar integrante por dni
Route::PATCH('/detalles-familiares/agregar-integrante', [PartnerController::class, 'agregarIntegranteGrupoFamiliar'])->name('agregar.integrante'); //ruta agregar integrante al grupo familiar


//Rutas para actividades y subactividades
Route::post('/inscripcion/subactividad', [ActivityController::class, 'subActividades'])->name('subactividades');
Route::post('/inscripcion/validar', [PartnerController::class, 'validarInscripcionSubActividad'])->name('validar.inscripcion');
Route::post('/inscripcion/registrar', [PartnerController::class, 'inscribirSocioSubActividad'])->name('registrar.inscripcion'); //ruta registrar inscripcion a subactividad

//Rutas para panel subactividades
Route::post('/panel-subactividades', [SubActividadController::class, 'traerSocios'])->name('panel-subactividades.socios');//ruta para traer socios de sub actividades
Route::PATCH('subactividades/baja-socio', [SubActividadController::class, 'bajaSocio'])->name('eliminar.socio'); //ruta eliminar socio










































































use App\Http\Controllers\OrderController;

  // Ruta para ordenes
Route::get('/ordenes/{id}/detalles', [OrderController::class, 'obtenerDetalles']);
Route::delete('/ordenes/detalles/{id}', [OrderController::class, 'eliminarDetalle'])->name('ordenes.detalles.eliminar');
Route::get('/ordenes/{id}/pdf', [OrderController::class, 'exportPdf'])->name('ordenes.pdf');