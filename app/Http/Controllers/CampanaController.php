<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SplStack;

class CampanaController extends Controller
{
    protected $pilaCampanas;

    public function __construct()
    {
        // Inicializa la pila de campañas
        $this->pilaCampanas = new SplStack();
        Log::info('CampanaController instanciado');
    }

    // Agregar campaña a la pila
    public function agregarCampana(Request $request)
{
    Log::info('Se está intentando agregar una campaña');

    $campana = [
        'titulo' => $request->input('titulo'),
        'descripcion' => $request->input('descripcion'),
        'ubicacion' => $request->input('ubicacion'),
        'fecha_inicio' => $request->input('fecha_inicio'),
        'fecha_creacion' => now(),
    ];
    $this->pilaCampanas->push($campana);

    Log::info('Campaña agregada: ', ['campana' => $campana]);

    return response()->json(['message' => 'Campaña agregada', 'campana' => $campana]);
}

    // Obtener la campaña en la cima
    public function obtenerCampana()
    {
        // Verificar si la pila está vacía
        if ($this->pilaCampanas->isEmpty()) {
            // Loguear que no hay campañas disponibles
            Log::warning('Intento de obtener campaña pero la pila está vacía');
            return response()->json(['message' => 'No hay campañas disponibles']);
        }

        // Obtener la campaña en la cima
        $campana = $this->pilaCampanas->top();

        // Loguear la campaña obtenida
        Log::info('Obteniendo campaña:', $campana);

        return response()->json(['campana' => $campana]);
    }

    // Sacar la campaña en la cima
    public function sacarCampana()
    {
        // Verificar si la pila está vacía
        if ($this->pilaCampanas->isEmpty()) {
            // Loguear que no hay campañas para sacar
            Log::warning('Intento de sacar campaña pero la pila está vacía');
            return response()->json(['message' => 'No hay campañas para sacar']);
        }

        // Sacar la campaña de la pila
        $campana = $this->pilaCampanas->pop();

        // Loguear la campaña que se ha sacado
        Log::info('Campaña sacada:', $campana);

        return response()->json(['message' => 'Campaña sacada', 'campana' => $campana]);
    }
}
