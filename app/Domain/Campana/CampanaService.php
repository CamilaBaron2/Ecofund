<?php

namespace App\Services;

use App\Domain\Campana\ICampanaService;
use App\Models\Campana;
use App\Http\Requests\CampanaRequest;
use Illuminate\Database\Eloquent\Collection;

class CampanaService implements ICampanaService
{
    /**
     * Buscar una campaña por su ID.
     *
     * @param int $id
     * @return Campana|null
     */
    public function buscarPorId(int $id): ?Campana
    {
        return Campana::find($id);
    }

    /**
     * Crear o actualizar una campaña.
     *
     * @param CampanaRequest $request
     * @return Campana
     */
    public function crear(CampanaRequest $request): Campana
    {
        return Campana::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ubicacion' => $request->ubicacion,
            'fecha_inicio' => $request->fecha_inicio,
        ]);
    }

    /**
     * Eliminar una campaña por su ID.
     *
     * @param int $id
     * @return void
     */
    public function eliminar(int $id): void
    {
        $campana = Campana::find($id);
        if ($campana) {
            $campana->delete();
        }
    }

    /**
     * Obtener todas las campañas.
     *
     * @return Collection
     */
    public function obtenerTodas(): Collection
    {
        return Campana::all();
    }
}
