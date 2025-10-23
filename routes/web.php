<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CashRecordController;
use App\Http\Controllers\CashRecordDetailController;
use App\Http\Controllers\InvoiceConstroller;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Middleware;
use App\Http\Controllers\SubActividadController;
use App\Http\Controllers\SupplierController;
use App\Models\Partner;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});


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

// Ruta para ordenes
Route::get('/ordenes/{id}/detalles', [OrderController::class, 'obtenerDetalles']);
Route::post('/ordenes/{id}/factura', [OrderController::class, 'generarFactura']);
Route::get('/ordenes/{id}/pdf', [OrderController::class, 'exportPdf'])->name('ordenes.pdf');


// Rutas para caja diaria
Route::post('/cajadiaria', [CashRecordController::class, 'cashRecords'])->name('caja.diaria');
Route::post('/cajadiaria/registros', [CashRecordController::class, 'cashRecord'])->name('caja.registros');
Route::post('/cajadiaria/registrar', [CashRecordDetailController::class, 'agregarMovimiento'])->name('caja.registrar');
Route::delete('/cajadiaria/eliminar/movimiento', [CashRecordDetailController::class, 'eliminarMovimiento'])->name('caja.eliminar');
Route::delete('/cajadiaria/eliminar/registro', [CashRecordController::class, 'eliminarCashRecord'])->name('caja.eliminar.registro');
Route::delete('/cajadiaria/eliminar/registro/vacio', [CashRecordController::class, 'eliminarCashRecordVacio'])->name('caja.eliminar.registro.vacio');


//Rutas para cobrar facturas de socio
Route::get('/facturas/impagas/{partner}', [InvoiceConstroller::class, 'facturasImpagas']);// ver todas las facturas impagas de un socio
Route::get('/facturas/pagas/{partner}', [InvoiceConstroller::class, 'facturasPagas']);
Route::post('/pagos/pagar-facturas', [PaymentController::class, 'pagarFacturas']); // Pagar/cobrar facturas


//Rutas parametros
Route::post('/parametros/buscar', [ParameterController::class, 'buscarParametros']);
Route::post('/parametro/cuota/cambio', [ParameterController::class, 'cambiarParametroSocial']);
Route::post('/parametro/actividad/cambio', [ParameterController::class, 'cambiarParametroActividad']);

//Ruta pdf factura
Route::post('/facturas/pagar', [InvoiceConstroller::class, 'pagarFacturasProveedor'])->name('factura.pagar');
Route::get('/factura/{id}/pdf', [InvoiceConstroller::class, 'exportPdf'])->name('factura.pdf');

//Ruta para los filtros
Route::post('/buscar/socio', [InvoiceConstroller::class, 'buscarSocio']);
Route::post('/subactividad/buscar', [SubActividadController::class, 'buscarSubactvidad']);
Route::post('/grupo-familiar/buscar', [PartnerController::class, 'buscarGrupo']);



//rutas vista socios
Route::get('/socio/login', function () {
  return view('partner.login');
})->name('login');
Route::post('/validacion/login', [PartnerController::class, 'validacionLogin'])->name('partner.login');

Route::middleware(['auth:partner'])->group(function () {
    Route::get('/panel/socio', [PartnerController::class, 'panelSocio'])->name('partner.panel');
});


Route::post('/logout/partner', function () {
    Auth::guard('partner')->logout();
    return redirect('/socio/login');
})->name('partner.logout');

Route::post('/socio/facturas/inpagas', [PartnerController::class, 'facturasInpagas']);
Route::post('/socio/facturas/pagas', [PartnerController::class, 'facturasPagas']);

Route::get('/partner/cambio/contrasena', function () {
    return view('partner.cambioContrasena');
})->name('partner.password.change');

Route::post('/partner/contrasena/cambiada', [PartnerController::class, 'cambiarContrasena'])->name('partner.contrasena.cambiada');

Route::post('/partner/cambio/imagen', [PartnerController::class, 'subirPerfil'])->name('partner.cambio.imagen');

Route::get('/parner/carnet', function () {
  return view('partner.carnetSocio');
})->name('socio.carnet');

//


//ruta moderacion de carnets

Route::post('traer/socios/fotos', [PartnerController::class, 'traerPerfilSocios']);
Route::post('aceptar/foto/socio', [PartnerController::class, 'aceptarFotoPerfil']);
Route::delete('eliminar/foto/socio', [PartnerController::class, 'eliminarFotoPerfil']);

//


