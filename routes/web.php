<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\InvoiceConstroller;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SubActividadController;
use App\Http\Controllers\SupplierController;
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


//Rutas para facturacion general e individual
Route::post('/facturar/socio', [InvoiceConstroller::class, 'facturacionMasivaMensualSocio'])->name('facturar.socio');//ruta para traer socios de sub actividades
Route::post('/facturar/buscarSocio', [PartnerController::class, 'buscarIntegrante'])->name('facturar.buscarSocio');
Route::post('/facturar/socio/individual', [InvoiceConstroller::class, 'facturarSocioIndividual'])->name('facturar.socio.individual');//ruta para traer socios de sub actividades

//Rutas para nota de credito
Route::post('/notacredito/buscar/proveedores', [SupplierController::class, 'proveedores'])->name('buscar.proveedores');
Route::post('/notacredito/proveedor/facturas', [SupplierController::class, 'facturasProveedores'])->name('proveedores.facturas');
Route::post('/notacredito/socio/facturas', [PartnerController::class, 'facturasSocios'])->name('socios.facturas');
Route::post('/notacredito/ventas/facturas', [SaleController::class, 'facturasVentas'])->name('ventas.facturas');
Route::delete('/notacredito/elimininar/facturas', [InvoiceConstroller::class, 'notaCreditoFactura'])->name('nota-credito.facturas');

