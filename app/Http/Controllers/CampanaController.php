<?php

namespace App\Http\Controllers;

use App\Domain\Campana\ICampanaService;
use App\Http\Requests\CampanaRequest;
use Illuminate\Support\Facades\Log;

class CampanaController extends Controller
{
    protected $campanaService;

    public function __construct(ICampanaService $campanaService)
    {
        $this->campanaService = $campanaService;
        Log::info('CampanaController instanciado');
    }

    // Métodos para la pila
    public function agregarCampanaPila(CampanaRequest $request)
    {
        Log::info('Intentando agregar campaña a la pila');
        $campana = $this->campanaService->agregarCampanaPila($request);
        return response()->json(['message' => 'Campaña agregada a la pila', 'campana' => $campana]);
    }

    public function obtenerCampanaPila()
    {
        Log::info('Obteniendo campaña de la pila');
        $campana = $this->campanaService->obtenerCampanaPila();

        if (!$campana) {
            return response()->json(['message' => 'No hay campañas disponibles en la pila']);
        }

        return response()->json(['campana' => $campana]);
    }

    public function sacarCampanaPila()
    {
        Log::info('Sacando campaña de la pila');
        $campana = $this->campanaService->sacarCampanaPila();

        if (!$campana) {
            return response()->json(['message' => 'No hay campañas para sacar de la pila']);
        }

        return response()->json(['message' => 'Campaña sacada de la pila', 'campana' => $campana]);
    }

    // Métodos para la base de datos
    public function buscarPorId($id)
    {
        $campana = $this->campanaService->buscarPorId($id);

        if (!$campana) {
            return response()->json(['message' => 'Campaña no encontrada'], 404);
        }

        return response()->json(['campana' => $campana]);
    }

    public function crear(CampanaRequest $request)
    {
        $campana = $this->campanaService->crear($request);
        return response()->json(['message' => 'Campaña creada en la base de datos', 'campana' => $campana]);
    }

    public function eliminar($id)
    {
        $this->campanaService->eliminar($id);
        return response()->json(['message' => 'Campaña eliminada']);
    }

    public function obtenerTodas()
    {
        $campanas = $this->campanaService->obtenerTodas();
        return response()->json(['campanas' => $campanas]);
    }
}
