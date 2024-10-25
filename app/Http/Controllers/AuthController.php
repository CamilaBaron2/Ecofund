<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;



class AuthController extends Controller
{
    // Registro de usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', ['errors' => $validator->errors()]);
            return response()->json($validator->errors(), 400);
        }

        // Generar un salt aleatorio
        $salt = bin2hex(random_bytes(16)); // Genera un salt aleatorio de 16 bytes

        // Crear la contraseña hasheada con bcrypt usando el salt y el pepper
        $hashedPassword = bcrypt($request->password . $salt . 'Ec07und');

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashedPassword,
            'salt' => $salt, // Guarda el salt en la base de datos
        ]);

        return response()->json(['user' => $user], 201);
    }

    // Inicio de sesión
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Encuentra el usuario por email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        // Combina la contraseña ingresada con el salt y el pepper
        $hashedInputPassword = bcrypt($credentials['password'] . $user->salt . 'Ec07und');

        // Verifica si la contraseña coincide
        if ($hashedInputPassword !== $user->password) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        // Genera el token
        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    // Refrescar token
    public function refresh()
    {
        return response()->json(['token' => JWTAuth::refresh()]);
    }

    // Cierre de sesión
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
}
