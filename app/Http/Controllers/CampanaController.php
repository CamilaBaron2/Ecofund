<?php

namespace App\Http\Controllers;

use App\Services\CampanaService;
use Illuminate\Http\Request;
use App\Models\Campana;

class CampanaController extends Controller
{
    protected $campanaService;

    public function __construct(CampanaService $campanaService)
    {
        $this->campanaService = $campanaService;
    }

    public function agregarCampana(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'titulo' => 'required|string|max:50',
            'descripcion' => 'required|string',
            'ubicacion' => 'required|string',
            'fecha_inicio' => 'required|date',
        ]);

        // Crear la nueva campaña
        $campana = Campana::create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'ubicacion' => $validated['ubicacion'],
            'fecha_inicio' => $validated['fecha_inicio'],
        ]);

        // Retornar respuesta de éxito
        return response()->json([
            'message' => 'Campaña agregada exitosamente',
            'data' => $campana,
        ], 201);
    }


    public function quitarCampana(Request $request)
    {
        // Validación de que se envíe el id
        $validated = $request->validate([
            'id' => 'required|exists:campanas,id',
        ]);

        // Buscar la campaña y eliminarla
        $campana = Campana::find($validated['id']);
        $campana->delete();

        // Respuesta de éxito
        return response()->json([
            'message' => 'Campaña eliminada exitosamente',
        ]);
    }


    public function verUltimaCampana()
    {
        // Obtener la última campaña
        $campana = Campana::latest()->first();

        // Verificar si existe una campaña
        if (!$campana) {
            return response()->json([
                'message' => 'No hay campañas registradas.',
            ], 404);
        }

        // Retornar la campaña
        return response()->json([
            'data' => $campana,
        ]);
    }


    public function obtenerTamanoPila()
    {
        // Obtener el número de campañas
        $tamano = Campana::count();

        // Retornar el tamaño
        return response()->json([
            'tamano' => $tamano,
        ]);
    }

    public function obtenerCampanas()
    {
        // Obtener todas las campañas
        $campanas = Campana::all();

        // Verificar si existen campañas
        if ($campanas->isEmpty()) {
            return response()->json([
                'message' => 'No hay campañas registradas.',
            ], 404);
        }

        // Retornar las campañas
        return response()->json([
            'data' => $campanas,
        ]);
    }
}
