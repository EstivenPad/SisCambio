<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;
use App\Permisos;
use App\Rol_Permiso;
use DB;

class RolController extends Controller
{
    public function getListaRoles(Request $request){
        $filtro = $request->filtro;
        $criterio = $request->criterio;
        $filtroaplicado = $request->filtroaplicado;

        if($criterio == ''){
            $roles = Rol::orderBy('id','desc')->get();
        }
        if($criterio != '' && $filtro != ''){
            $roles = Rol::where($filtro,'like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }

        return $roles;
    }

    public function setRegistrarRol(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $rol = new Rol();
        $rol->name = $request->name;
        $rol->slug = $request->slug;
        $rol->created_at = now();
        $rol->save();

        try {
            DB::beginTransaction();

            $permisos = $request->permisos;
            $tamano = sizeof($permisos);

            if($tamano > 0){
                foreach($permisos as $key => $item){
                    if($item['checked'] == true){
                        $detalle = new Rol_Permiso();
                        $detalle->role_id = $rol->id;
                        $detalle->permission_id = $item['id'];  
                        $detalle->save();                      
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function getRolPermisoEditar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $rol = Rol::findOrFail($request->id);

        return $rol;
    }

    public function setEditarRolPermiso(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $rol = Rol::findOrFail($request->id);
        $rol->slug = $request->slug;
        $rol->name = $request->nombre;
        $rol->updated_at = now();
        $rol->save();

        try {
            DB::beginTransaction();

            $permisos = $request->permisos;
            $tamano = sizeof($permisos);
            
            if($tamano > 0){
                foreach($permisos as $key => $item){
                    Rol_Permiso::where('role_id','=',$request->id)->delete();
                }
                foreach($permisos as $key => $item){                     
                    if($item['checked'] == true){
                     
                        $detalle = new Rol_Permiso();
                        $detalle->role_id = $rol->id;
                        $detalle->permission_id = $item['id'];  
                        $detalle->save();                      
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
        }
    }

    public function getPermisosByRol(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $modulos = DB::table('permissions')->select('modulo',DB::raw("COUNT(id) as total"))->groupBy('modulo')->get();
        $permisos = Permisos::orderBy('id', 'desc')->get();
        $permisosChecked = Rol_Permiso::where('role_id', '=', $request->id)->get();

        return ['modulos' => $modulos, 'permisos' => $permisos, 'permisosChecked' => $permisosChecked];
    }
}
