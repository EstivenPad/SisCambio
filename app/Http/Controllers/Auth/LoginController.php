<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $usuario = $request->usuario;
        $contrasena = $request->contrasena;

        $respuesta = Auth::attempt(['usuario' => $usuario, 'pass' => $contrasena]);

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
}
