<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Storage;


Route::get('/', 'InicioController@index');

Route::get('/clientes','ClienteController@index');
Route::get('/vendedores','VendedorController@index');
Route::get('/adicionales','AdicionalController@index');
Route::get('/ventas','VentaController@index');
Route::get('/cuentasTitulares','CuentaController@index');
Route::get('/cuentasAdicionales','CuentaAdicionalController@index');
Route::get('/informes','InformeController@index');

Route::post('uploadCliente','ClienteController@store');
Route::post('uploadVendedor','VendedorController@store');
Route::post('uploadAdicional','AdicionalController@store');
Route::post('uploadVenta','VentaController@store');
Route::post('uploadAbono','CuentaController@store');

Route::get('showCliente/{id}', 'ClienteController@show');
Route::get('showDocumento/{id}', 'DocumentoController@show');
Route::get('showVendedor/{id}', 'VendedorController@show');
Route::get('showAdicional/{id}', 'AdicionalController@show');
Route::get('showVenta/{id}', 'VentaController@show');
Route::get('showProducto/{id}', 'ProductoController@show');
Route::get('showDeudaMensual/{id}', 'DeudaMensualController@show');

Route::get('showClientePorDeuda/{id}/{idDeuda}', 'ClienteController@showClientePorDeuda');

Route::post('updateCliente/{id}', 'ClienteController@update');
Route::post('updateVendedor/{id}', 'VendedorController@update');
Route::post('updateAdicional/{id}', 'AdicionalController@update');
Route::post('updateVenta/{id}', 'VentaController@update');

Route::get('deleteDocumento/{id}', 'DocumentoController@destroy');
Route::post('deleteAdicional/{id}', 'AdicionalController@destroy');
Route::post('anularVenta/{id}', 'VentaController@destroy');


Route::get('download/{file}', function ($file) {
    return Storage::download($file);
});

Route::get('nombreCliente/{rut}', 'ClienteController@cargaNombreCliente');
Route::get('cargaDatosClienteVenta/{rut}', 'ClienteController@cargaDatosClienteVenta');
Route::get('revisionBloqueoEliminarVenta/{id}', 'VentaController@revisionBloqueo');
Route::get('revisaNumeroBoleta/{nBoleta}', 'VentaController@revisionBoleta');
Route::get('cambiarBloqueoCliente/{id}/{bloqueo}', 'ClienteController@cambiarBloqueoCliente');
Route::get('cargarMora/{id}/{monto}', 'CuentaController@cargarMora');
Route::get('eliminarMora/{id}', 'CuentaController@eliminarMora');

Route::get('cambiarBloqueoAdicional/{id}/{bloqueo}', 'AdicionalController@cambiarBloqueoAdicional');

Route::get('showCuentaVentas/{id}', 'CuentaController@showCuentaVentas');
Route::get('showCuentaVentasAdicional/{id}', 'CuentaAdicionalController@showCuentaVentasAdicional');
Route::get('showDeudaVentas/{id}', 'CuentaController@showDeudaVentas');
Route::get('showAbonoCuenta/{id}', 'CuentaController@showAbonoCuenta');

Route::get('getClienteJson/{id}', 'CuentaController@getClienteJson');

Route::post('crearComprobanteVenta','PdfController@crearPDFVenta');

Route::get('datosGraficoIngresos','InicioController@datosGraficoIngresos');

Route::get('imprimirAbono/{idAbono}', 'CuentaController@imprimirPago');

Route::get('/getClientesExcel','InformeController@getClientesExcel');
Route::get('/getAdicionalesExcel','InformeController@getAdicionalesExcel');
Route::get('/getClientesMorososExcel','InformeController@getClientesMorososExcel');
Route::post('/getVentasExcel','InformeController@getVentasExcel');
Route::post('/getPagosExcel','InformeController@getPagosExcel');
Route::post('/getComprasClienteExcel','InformeController@getComprasClienteExcel');
Route::post('/getPagosClienteExcel','InformeController@getPagosClienteExcel');
Route::post('/getMoraClienteExcel','InformeController@getMoraClienteExcel');

