<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaccion;
use App\Cheque;
use App\Numero;
use App\Cliente;
use DB;

class TransaccionController extends Controller
{
    public function getListaTransacciones(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $filtro = $request->filtro;
        $criterio = $request->criterio;
        $filtroaplicado = $request->filtroaplicado;
        
        $transacciones = ''; //Inicializo la variable transacciones por si acaso no logra entrar en alguna condicion
        
        //Si no hay un criterio establecido ni se ha aplicado algun filtro
        if($criterio == '' && $filtroaplicado == 0){
            $transacciones = DB::table('transaccion')
            ->join('moneda','transaccion.moneda_id','=','moneda.id')
            ->select('transaccion.*', 'moneda.nombre')
            ->orderBy('id','desc')
            ->get();
        }        
        //Si se desea filtrar por los registros activos, sin importar que el criterio este lleno o vacio
        if($filtro == 'A'){
            $transacciones = Transaccion::where('estado','=',1)->orderBy('id','desc')->get();    
        }
        //Si se desea filtrar por los registros inactivos, sin importar que el criterio este lleno o vacio
        if($filtro == 'I'){
            $transacciones = Transaccion::where('estado','=',0)->orderBy('id','desc')->get();    
        }
        //Si se tiene un criterio y un filtro seleccionado, y dicho filtro no es "Activo" o "Inactivo"
        if($criterio != '' && $filtroaplicado == 1 && $filtro != 'A' && $filtro != 'I'){ 
            $transacciones = Transaccion::where($filtro,'like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }
        
        return $transacciones;
    }

    public function setRegistrarTransaccionCompraVenta(Request $request)
    {   
        if(!$request->ajax()) return redirect('/');

        $num = Numero::findOrFail(1);

        $transaccion = new Transaccion();
        
        $transaccion->fecha = now();
        $transaccion->tipo = $request->tipo;
        $transaccion->numero = $num->vc;
        $transaccion->moneda_id = $request->moneda_id;
        $transaccion->cantidadPeso = $request->cantidadPeso;
        $transaccion->cantidadDivisa = $request->cantidadDivisa;
        $transaccion->precio = $request->precio;
        $transaccion->total = $request->total;
        $transaccion->estado = 1;
        $transaccion->save();

        $num->vc = $num->vc + 1;
        $num->save();
    }

    public function setCambiarEstadoTransaccion(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $transaccion = Transaccion::findOrFail($request->id);
        $transaccion->estado = ($transaccion->estado == 1 ? 0 : 1);
        $transaccion->save(); 
    }

    public function getMonedas(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $monedas = DB::table('moneda')->get();

        return $monedas;
    }

    public function getBancos(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $bancos = DB::table('banco')->get();

        return $bancos;
    }

    public function getClientes(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $clientes = DB::table('cliente')->get();

        return $clientes;
    }

    public function setRegistrarCliente(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $cliente = new Cliente();

        $cliente->nombre = $request->nombre;
        $cliente->apellido = $request->apellido;
        $cliente->estado = 1;
        $cliente->creado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $cliente->actualizado_por = 1; //Cambiar el valor de esta parte cuando se tenga el modulo de "Usuarios" terminado
        $cliente->created_at = now();
        $cliente->save();
    }

    public function setRegistrarTransaccionCheque(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $num = Numero::findOrFail(1);

        $transaccion = new Cheque();
        
        $transaccion->fecha = now();
        $transaccion->fecha_emision = $request->fecha_emision;
        $transaccion->banco_id = $request->banco_id;
        $transaccion->persona_id = $request->persona_id;
        $transaccion->moneda_id = $request->moneda_id;
        $transaccion->numero = $num->cheque;
        $transaccion->numeroCheque = $request->numeroCheque;
        
        if($request->concepto){
            $transaccion->concepto = $request->concepto;
        }

        $transaccion->monto = $request->monto;
        $transaccion->porcentajeComision = $request->porcentajeComision;
        $transaccion->comision = $request->comision;
        $transaccion->totalEntregar = $request->totalEntregar;
        $transaccion->estado = 1;
        $transaccion->save();

        $num->cheque = $num->cheque + 1;
        $num->save();
    }
}
