<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AuthController extends Controller
{
    // Muestra el formulario de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Procesa el registro de un nuevo usuario
    public function register(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'nombre_usuario' => 'required|string|max:50|unique:usuarios',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Crear un nuevo usuario en la base de datos
        $usuario = Usuario::create([
            'nombre_usuario' => $request->nombre_usuario,
            'password' => Hash::make($request->password),
            'rol' => 'colaborador', // Por defecto, el rol es 'colaborador'
        ]);

        // 3. Iniciar sesión automáticamente después del registro
        Auth::login($usuario);

        // 4. Redirigir al usuario
        return redirect()->intended('/dashboard');
    }

    // Muestra el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesa el inicio de sesión
    public function login(Request $request)
    {
        // 1. Validar los datos
        $credentials = $request->validate([
            'nombre_usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Intentar autenticar al usuario
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirigir a la URL anterior o a la principal
            return redirect()->intended('/dashboard');
        }

        // 3. Si la autenticación falla, redirigir con un error
        return back()->withErrors([
            'nombre_usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('nombre_usuario');
    }

    // Cierra la sesión del usuario
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}