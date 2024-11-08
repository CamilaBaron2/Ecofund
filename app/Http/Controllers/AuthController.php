<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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
        $salt = bin2hex(random_bytes(16));

        // Crear la contraseña hasheada usando el salt y un pepper estático
        $hashedPassword = bcrypt($request->password . $salt . 'Ec07und');

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
                'salt' => $salt,
            ]);

            // Log para verificar el salt y la contraseña hasheada
            Log::info('Salt generado: ' . $salt);
            Log::info('Contraseña hasheada: ' . $hashedPassword);

            $token = JWTAuth::fromUser($user);

            return response()->json(['user' => $user, 'token' => $token], 201);
        } catch (\Exception $e) {
            Log::error('Error en el registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error en el registro'], 500);
        }
    }

// Inicio de sesión
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    $user = User::where('email', $credentials['email'])->first();

    if (!$user) {
        return response()->json(['error' => 'invalid_credentials'], 401);
    }

    // Log para verificar el proceso
    Log::info('Intentando iniciar sesión con usuario: ' . $user->email);

    // Crear la contraseña combinada sin encriptar
    $inputPassword = $credentials['password'] . $user->salt . 'Ec07und';

    // Log para verificar la contraseña combinada y la almacenada

    Log::info('Contraseña combinada ingresada: ' . $inputPassword);

    // Comparar el hash de la contraseña combinada con el hash almacenado en la base de datos
    if (!Hash::check($inputPassword, $user->password)) {
        Log::info('Contraseña almacenada en la base de datos oficial: ' . $user->password);
        Log::warning('Contraseña no válida para el usuario: ' . $user->email);
        return response()->json(['error' => 'invalid_credentials'], 401);
    }

    // Generar el token JWT
    $token = JWTAuth::fromUser($user);
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


