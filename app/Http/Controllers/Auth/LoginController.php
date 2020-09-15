<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $usuario = $request->usuario;
        $contrasena = $request->contrasena;
        
        $respuesta = Auth::attempt(['usuario' => $usuario, 'password' => $contrasena, 'estado' => 1]);
        
        if($respuesta){
            return response()->json([
                'authUser' => Auth::user(),
                'code' => 200
            ]);
        }else{    
            return response()->json([
                'code' => 401
            ]);
        }
    }

    public function logout(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        Auth::logout();

        return response()->json([
            'code' => 204
        ]);
    }
}
