<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TransaccionController extends Controller
{
    public function getListaTransacciones(Request $request)
    {
        return;
    }

    public function setRegistrarTransaccion(Request $request)
    {
        return;
    }

    public function getMonedas(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $monedas = DB::table('moneda')->get();

        return $monedas;
    }
}
