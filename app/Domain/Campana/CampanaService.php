<?php

namespace App\Services;

use App\Domain\Campana\ICampanaService;
use App\Models\Campana;
use App\Http\Requests\CampanaRequest;
use Illuminate\Database\Eloquent\Collection;
use SplStack;
use Illuminate\Support\Facades\Log;

class CampanaService implements ICampanaService
{
    protected $pilaCampanas;

    public function __construct()
    {
        $this->pilaCampanas = new SplStack();
    }

    // Métodos para la pila
    public function agregarCampanaPila(CampanaRequest $request): array
    {
        $campana = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ubicacion' => $request->ubicacion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_creacion' => now(),
        ];

        $this->pilaCampanas->push($campana);
        Log::info('Campaña agregada a la pila: ', ['campana' => $campana]);

        return $campana;
    }

    public function obtenerCampanaPila(): ?array
    {
        if ($this->pilaCampanas->isEmpty()) {
            Log::warning('Intento de obtener campaña pero la pila está vacía');
            return null;
        }

        return $this->pilaCampanas->top();
    }

    public function sacarCampanaPila(): ?array
    {
        if ($this->pilaCampanas->isEmpty()) {
            Log::warning('Intento de sacar campaña pero la pila está vacía');
            return null;
        }

        $campana = $this->pilaCampanas->pop();
        Log::info('Campaña sacada de la pila:', $campana);

        return $campana;
    }

    // Métodos para la base de datos
    public function buscarPorId(int $id): ?Campana
    {
        return Campana::find($id);
    }

    public function crear(CampanaRequest $request): Campana
    {
        return Campana::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ubicacion' => $request->ubicacion,
            'fecha_inicio' => $request->fecha_inicio,
        ]);
    }

    public function eliminar(int $id): void
    {
        $campana = Campana::find($id);
        if ($campana) {
            $campana->delete();
        }
    }

    public function obtenerTodas(): Collection
    {
        return Campana::all();
    }
}
