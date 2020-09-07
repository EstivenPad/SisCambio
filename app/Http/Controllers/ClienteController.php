<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Cliente;

class ClienteController extends Controller
{
    public function getListaClientes(Request $request)  
    {
        if(!$request->ajax()) return redirect('/');
        
        $filtro = $request->filtro;
        $criterio = $request->criterio;
        $filtroaplicado = $request->filtroaplicado;
        
        $clientes = ''; //Inicializo la variable clientes por si acaso no logra entrar en alguna condicion
        
        //Si no hay un criterio establecido ni se ha aplicado algun filtro
        if($criterio == '' && $filtroaplicado == 0){
            $clientes = Cliente::orderBy('id','desc')->get();            
        }        
        //Si se desea filtrar por los registros activos, sin importar que el criterio este lleno o vacio
        if($filtro == 'A'){
            $clientes = Cliente::where('estado','=',1)->orderBy('id','desc')->get();    
        }
        //Si se desea filtrar por los registros inactivos, sin importar que el criterio este lleno o vacio
        if($filtro == 'I'){
            $clientes = Cliente::where('estado','=',0)->orderBy('id','desc')->get();    
        }
        //Si se tiene un criterio y un filtro seleccionado, y dicho filtro no es "Activo" o "Inactivo"
        if($criterio != '' && $filtroaplicado == 1 && $filtro != 'A' && $filtro != 'I'){ 
            $clientes = Cliente::where($filtro,'like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }
        
        return $clientes;
    }

    public function setRegistrarCliente(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $cliente = new Cliente();

        //Si se esta enviando una imagen, se almacena la ruta publica de esa imagen
        if($request->imagen){
            $path = Storage::putFile('public/cliente', $request->file('imagen'));

            //Recorto la cadena, quitando los primeros 7 caracteres, para que la ruta de la imagen aparezca sin el "public/"
            $path = substr($path, 7);
            
            $cliente->imagen = $path;
        }

        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->telefono = $request->telefono;
        $cliente->celular = $request->celular;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->estado = 1;
        $cliente->creado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $cliente->actualizado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $cliente->created_at = now();
        $cliente->save();
    }

    public function setEditarCliente(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $cliente = Cliente::findOrFail($request->id);
        
        //Si se esta enviando una imagen, se almacena la ruta publica de esa imagen
        if($request->imagen){
            $path = Storage::putFile('public/cliente', $request->file('imagen'));

            //Recorto la cadena, quitando los primeros 7 caracteres, para que la ruta de la imagen aparezca sin el "public/"
            $path = substr($path, 7);
            
            $cliente->imagen = $path;
        }

        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->telefono = $request->telefono;
        $cliente->celular = $request->celular;
        $cliente->email = $request->email;
        $cliente->direccion = $request->direccion;
        $cliente->actualizado_por = 1; //Cambiar esta parte cuando se tenga el modulo de Usuarios
        $cliente->updated_at = now();

        $cliente->save();
      
    }

    public function getClienteEditar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $cliente = Cliente::findOrFail($request->id);
        
        return $cliente;
    }

    public function setCambiarEstadoCliente(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = ($cliente->estado == 1 ? 0 : 1);
        $cliente->save(); 
    }
}
