<?php

namespace App\Http\Controllers;

use App\Services\ReciclajeService;
use Illuminate\Http\Request;
use App\Models\Reciclaje;
use Illuminate\Support\Facades\Log;

class ReciclajeController extends Controller
{
    protected $reciclajeQueueService;

    public function __construct(ReciclajeService $reciclajeQueueService)
    {
        $this->reciclajeQueueService = $reciclajeQueueService;
    }

    /**
     * Agregar reciclaje a la cola
     */
    public function agregarReciclaje(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'titulo' => 'required|string|max:50',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|url',
            'fecha_inicio' => 'required|date',
        ]);

        // Registrar el inicio del proceso
        Log::info('Iniciando el proceso de agregar reciclaje', ['titulo' => $validated['titulo']]);

        try {
            // Agregar la campaña a la cola
            $this->reciclajeQueueService->agregarCampana($validated);

            // Retornar respuesta exitosa
            return response()->json([
                'message' => 'Campaña agregada a la cola',
                'data' => $validated,
            ], 201);
        } catch (\Exception $e) {
            // Manejar errores
            Log::error('Error al agregar la campaña a la cola', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error al agregar la campaña a la cola',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Procesar reciclaje desde la cola
     */
    public function procesarCola()
    {
        Log::info('Buscando campañas en la base de datos para procesar');

        // Recupera las campañas ordenadas por created_at en orden descendente (última agregada primero)
        $campanas = Reciclaje::orderBy('created_at', 'desc')->get();

        // Verifica que se están recuperando correctamente las campañas
        Log::info('Campañas encontradas', ['campanas' => $campanas]);

        if ($campanas->isEmpty()) {
            Log::warning('No hay campañas en la cola para procesar');
            return response()->json([
                'message' => 'No hay campañas en la cola para procesar'
            ], 404);
        }

        // Lógica para procesar las campañas
        foreach ($campanas as $campana) {
            Log::info('Procesando campaña', ['campana' => $campana]);
        }

        // Retorno de éxito o más detalles
        return response()->json([
            'message' => 'Campañas procesadas correctamente',
            'data' => $campanas // Retorna las campañas ordenadas en el formato adecuado
        ]);
    }

}

