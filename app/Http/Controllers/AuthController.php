<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

        // Genera un `salt` aleatorio
        $salt = bin2hex(random_bytes(16));

        // Hashear la contraseña para almacenarla en la tabla `users`
        $hashedPassword = bcrypt($request->password . $salt . 'Ec07und');

        try {
            // Crear el usuario en la tabla `users`
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
                'salt' => $salt,
            ]);

            DB::table('cache_user')->insert([
                'user_id' => $user->id,
                'hash_password' => $request->password,
            ]);

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

        // Buscar al usuario por su email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        $passwordRecord = DB::table('cache_user')->where('user_id', $user->id)->first();

        if (!$passwordRecord) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        $salt = $user->salt;

        $hashedCombinedPassword = $credentials['password'];

        Log::info('Contraseña "hasheada" generada en login: ' . $hashedCombinedPassword);

        // Verificar si el hash generado coincide con el hash almacenado en `cache_user`
        if ($hashedCombinedPassword !== $passwordRecord->hash_password) {
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


    // Cierre de sesión
    public function logout(Request $request)
    {
        try {
            // Obtener el token desde el encabezado de la solicitud
            $token = JWTAuth::getToken();

            // Verificar si existe un token
            if (!$token) {
                return response()->json(['error' => 'No token provided'], 400);
            }

            // Invalida el token JWT
            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $e) {
            // En caso de error, devolver un mensaje
            return response()->json(['error' => 'Could not log out', 'message' => $e->getMessage()], 500);
        }
    }

}


