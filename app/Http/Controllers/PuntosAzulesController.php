<?php

namespace App\Http\Controllers;

use App\Services\PuntosAzulesService;
use Illuminate\Http\Request;
use App\Models\PuntosAzules;
use Illuminate\Support\Facades\Log;

class PuntosAzulesController extends Controller
{
    protected $puntosAzulesService;

    public function __construct(PuntosAzulesService $puntosAzulesService)
    {
        $this->puntosAzulesService = $puntosAzulesService;
    }

    /**
     * Agregar una nueva campaña de puntos azules
     */
    public function agregarPuntoAzul(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:50',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|url',
            'fecha_inicio' => 'required|date',
        ]);

        Log::info('Iniciando el proceso de agregar punto azul', ['titulo' => $validated['titulo']]);

        try {
            $this->puntosAzulesService->agregarCampana($validated);

            return response()->json([
                'message' => 'Punto azul agregado exitosamente',
                'data' => $validated,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al agregar el punto azul', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Error al agregar el punto azul',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Procesar campañas de puntos azules desde la base de datos
     */
    public function procesarPuntosAzules()
    {
        Log::info('Buscando campañas de puntos azules en la base de datos');

        $campanas = PuntosAzules::orderBy('created_at', 'desc')->get();

        if ($campanas->isEmpty()) {
            Log::warning('No hay campañas de puntos azules para procesar');
            return response()->json([
                'message' => 'No hay campañas de puntos azules para procesar'
            ], 404);
        }

        foreach ($campanas as $campana) {
            Log::info('Procesando campaña de puntos azules', ['campana' => $campana]);
        }

        return response()->json([
            'message' => 'Campañas de puntos azules procesadas correctamente',
            'data' => $campanas
        ]);
    }
}
