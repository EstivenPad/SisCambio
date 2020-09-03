<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Moneda;

class MonedaController extends Controller
{
    public function getListaMonedas(Request $request)  
    {
        if(!$request->ajax()) return redirect('/');
        
        $criterio = $request->criterio;
        
        $monedas = ''; //Inicializo la variable monedas por si acaso no logra entrar en alguna condicion
        
        if($criterio == ''){
            $monedas = Moneda::orderBy('id','desc')->get();            
        }else{
            $monedas = Moneda::where('nombre','like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }
        
        return $monedas;
    }

    public function setRegistrarMoneda(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $moneda = new Moneda();

        $moneda->nombre = $request->nombre;
        $moneda->precioCompra = $request->precioCompra;
        $moneda->precioVenta = $request->precioVenta;
        $moneda->estado = 1;
        $moneda->creado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $moneda->actualizado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $moneda->created_at = now();
        $moneda->save();
    }

    public function setEditarMoneda(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $moneda = Moneda::findOrFail($request->id);
        
        $cliente->nombre = $request->nombre;
        $cliente->precioCompra = $request->precioCompra;
        $cliente->precioVenta = $request->precioVenta;
        $cliente->actualizado_por = 1; //Cambiar esta parte cuando se tenga el modulo de Usuarios
        $cliente->updated_at = now();

        $cliente->save();
    }

    public function getMonedaEditar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $moneda = Moneda::findOrFail($request->id);
        
        return $moneda;
    }

    public function setCambiarEstadoMoneda(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $moneda = Moneda::findOrFail($request->id);
        $moneda->estado = ($moneda->estado == 1 ? 0 : 1);
        $moneda->save(); 
    }
}
