<?php

use Illuminate\Support\Facades\Route;

/*************** Usuario ******************/
Route::get('/usuario/getListaUsuarios', 'UsuarioController@getListaUsuarios');
Route::post('/usuario/setRegistrarUsuario', 'UsuarioController@setRegistrarUsuario');
Route::get('/usuario/getUsuarioEditar', 'UsuarioController@getUsuarioEditar');
Route::post('/usuario/setEditarUsuario', 'UsuarioController@setEditarUsuario');
Route::post('/usuario/setCambiarEstadoUsuario', 'UsuarioController@setCambiarEstadoUsuario');

/*************** Cliente ******************/

Route::get('/cliente/getListaClientes', 'ClienteController@getListaClientes');
Route::post('/cliente/setRegistrarCliente', 'ClienteController@setRegistrarCliente');
Route::get('/cliente/getClienteEditar', 'ClienteController@getClienteEditar');
Route::post('/cliente/setEditarCliente', 'ClienteController@setEditarCliente');
Route::put('/cliente/setCambiarEstadoCliente', 'ClienteController@setCambiarEstadoCliente');

/*************** Moneda ******************/

Route::get('/moneda/getListaMonedas', 'MonedaController@getListaMonedas');
Route::post('/moneda/setRegistrarMoneda', 'MonedaController@setRegistrarMoneda');
Route::get('/moneda/getMonedaEditar', 'MonedaController@getMonedaEditar');
Route::post('/moneda/setEditarMoneda', 'MonedaController@setEditarMoneda');
Route::put('/moneda/setCambiarEstadoMoneda', 'MonedaController@setCambiarEstadoMoneda');

Route::get('/{optional?}', function () {
    return view('app');
})->name('basepath')->where('optional', '.*');
