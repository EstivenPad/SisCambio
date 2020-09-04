<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Almacen;

class AlmacenController extends Controller
{
    public function getListaAlmacenes(Request $request)  
    {
        if(!$request->ajax()) return redirect('/');
        
        $criterio = $request->criterio;
        
        $almacenes = ''; //Inicializo la variable almacenes por si acaso no logra entrar en alguna condicion
        
        if($criterio == ''){
            $almacenes = Almacen::orderBy('id','desc')->get();            
        }else{
            $almacenes = Almacen::where('nombre','like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }
        
        return $almacenes;
    }

    public function setRegistrarAlmacen(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $almacen = new Almacen();

        $almacen->nombre = $request->nombre;
        $almacen->descripcion = $request->descripcion;
        $almacen->estado = 1;
        $almacen->creado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $almacen->actualizado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $almacen->created_at = now();
        $almacen->save();
    }

    public function setEditarAlmacen(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $almacen = Almacen::findOrFail($request->id);
        
        $almacen->nombre = $request->nombre;
        $almacen->descripcion = $request->descripcion;
        $almacen->actualizado_por = 1; //Cambiar esta parte cuando se tenga el modulo de Usuarios
        $almacen->updated_at = now();

        $almacen->save();
    }

    public function getAlmacenEditar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $almacen = Almacen::findOrFail($request->id);
        
        return $almacen;
    }

    public function setCambiarEstadoAlmacen(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $almacen = Almacen::findOrFail($request->id);
        $almacen->estado = ($almacen->estado == 1 ? 0 : 1);
        $almacen->save(); 
    }
}
