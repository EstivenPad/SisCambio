<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Usuario;
use App\Usuario_Rol;
use DB;

class UsuarioController extends Controller
{
    public function getListaUsuarios(Request $request)
    {
       if(!$request->ajax()) return redirect('/');

       $filtro = $request->filtro;
       $criterio = $request->criterio;
       $filtroaplicado = $request->filtroaplicado;
        
        //Si no hay un criterio establecido ni se ha aplicado algun filtro
        if($criterio == '' && $filtroaplicado == 0){
            $usuarios = Usuario::orderBy('id','desc')->get();            
        }        
        //Si se desea filtrar por los registros activos, sin importar que el criterio este lleno o vacio
        if($filtro == 'A'){
            $usuarios = Usuario::where('estado','=',1)->orderBy('id','desc')->get();    
        }
        //Si se desea filtrar por los registros inactivos, sin importar que el criterio este lleno o vacio
        if($filtro == 'I'){
            $usuarios = Usuario::where('estado','=',0)->orderBy('id','desc')->get();    
        }
        //Si se tiene un criterio y un filtro seleccionado, y dicho filtro no es "Activo" o "Inactivo"
        if($criterio != '' && $filtroaplicado == 1 && $filtro != 'A' && $filtro != 'I'){ 
            $usuarios = Usuario::where($filtro,'like','%'.$criterio.'%')->orderBy('id','desc')->get();
        }

        return $usuarios;
    }

    public function setCambiarEstadoUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $usuario = Usuario::findOrFail($request->id);
        $usuario->estado = ($usuario->estado == 1 ? 0 : 1);
        $usuario->save(); 
    }

    public function setRegistrarUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $usuario = new Usuario();

        //Si se esta enviando una imagen, se almacena la ruta publica de esa imagen
        if($request->imagen){
            $path = Storage::putFile('public/usuario', $request->file('imagen'));
            
            //Recorto la cadena, quitando los primeros 7 caracteres, para que la ruta de la imagen aparezca sin el "public/"
            $path = substr($path, 7);
            $usuario->imagen = $path;
        }

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->usuario = $request->usuario;
        $usuario->password = Hash::make($request->password);
        $usuario->estado = 1;
        $usuario->creado_por = 1;
        $usuario->actualizado_por = 1;
        $usuario->created_at = now();
        $usuario->save();

        $usuario_guardado = DB::table('users')->select(DB::raw('MAX(id) as id'))->get();
        
        return $usuario_guardado[0]->id;
    }

    public function getUsuarioEditar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $usuario = Usuario::findOrFail($request->id);
        
        return $usuario;
    }

    public function setEditarUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $usuario = Usuario::findOrFail($request->id);
        
        //Si se esta enviando una imagen, se almacena la ruta publica de esa imagen
        if($request->imagen){
            $path = Storage::putFile('public/usuario', $request->file('imagen'));
            
            //Recorto la cadena, quitando los primeros 7 caracteres, para que la ruta de la imagen aparezca sin el "public/"
            $path = substr($path, 7);
            $usuario->imagen = $path;
        }

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->usuario = $request->usuario;     
        $usuario->creado_por = 1;
        $usuario->actualizado_por = 1;
        $usuario->updated_at = now();
        $usuario->save();
    }

    public function setGuardarRolUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $respuesta = Usuario_Rol::where('user_id','=',$request->user_id);

        if($respuesta){
            Usuario_Rol::where('user_id','=',$request->user_id)->delete();
        }
        
        $usuario_rol = new Usuario_Rol();
        
        $usuario_rol->user_id = $request->user_id;
        $usuario_rol->role_id = $request->rol_id;
        $usuario_rol->save();
    }

    public function getRolByUsuario(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $rol = Usuario_Rol::where('user_id', '=', $request->id)->get();
        
        return $rol;
    }

    public function getListarPermisosByRol(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $id = $request->id;

        if(!$id){
            $id = Auth::id();
        }   

        $respuesta = DB::table('users_roles')
                    ->join('roles', 'roles.id', '=', 'users_roles.role_id')
                    ->join('roles_permissions', 'roles.id', '=', 'roles_permissions.role_id')
                    ->join('permissions', 'roles_permissions.permission_id', '=', 'permissions.id')
                    ->select('permissions.name', 'permissions.slug', 'permissions.modulo')
                    ->where('users_roles.user_id', '=', $id)
                    ->get();

        return $respuesta;
    }
}
