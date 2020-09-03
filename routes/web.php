<?php

use Illuminate\Support\Facades\Route;

Route::get('/cliente/getListaClientes', 'ClienteController@getListaClientes');
Route::post('/cliente/setRegistrarCliente', 'ClienteController@setRegistrarCliente');
Route::get('/cliente/getClienteEditar', 'ClienteController@getClienteEditar');
Route::post('/cliente/setEditarCliente', 'ClienteController@setEditarCliente');
Route::put('/cliente/setCambiarEstadoCliente', 'ClienteController@setCambiarEstadoCliente');

Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')->where('optional', '.*');
