<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permisos;
class PermisosController extends Controller
{
    //
    public function getListaPermisos(Request $request){
        $filtro = $request->filtro;
        $criterio = $request->criterio;
        $filtroaplicado = $request->filtroaplicado;

        if($criterio == ''){
            $permisos = Permisos::orderBy('id','desc')->get();
        }
        if($criterio != '' &&   $filtro != ''){
            $permisos = Permisos::where($filtro,'like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }


        return $permisos;
    }
}
