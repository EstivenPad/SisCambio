<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permisos;
class PermisosController extends Controller
{
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

    public function setRegistrarPermiso(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $permiso = new Permisos();
        $permiso->slug = $request->slug;
        $permiso->name = $request->nombre;
        $permiso->modulo = $request->modulo;
        $permiso->created_at = now();
        $permiso->save();
    }

    public function getPermisoEditar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $permiso = Permisos::findOrFail($request->id);

        return $permiso;
    }

    public function setEditarPermiso(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $permiso = Permisos::findOrFail($request->id);
        $permiso->slug = $request->slug;
        $permiso->name = $request->nombre;
        $permiso->modulo = $request->modulo;
        $permiso->updated_at = now();
        $permiso->save();
    }
}
