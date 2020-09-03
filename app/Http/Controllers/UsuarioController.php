<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Usuario;

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
        $usuario->pass = Hash::make($request->pass);
        $usuario->estado = 1;
        $usuario->creado_por = 1;
        $usuario->actualizado_por = 1;
        $usuario->created_at = now();
        $usuario->save();
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
        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->usuario = $request->usuario;
        $usuario->imagen = $request->imagen;        
        $usuario->creado_por = 1;
        $usuario->actualizado_por = 1;
        $usuario->updated_at = now();
        $usuario->save();
    }
}
