<?php

namespace App\Domain\Campana;

use App\Models\Campana;
use App\Http\Requests\CampanaRequest;
use Illuminate\Database\Eloquent\Collection;

interface ICampanaService
{
    /**
     * Buscar una campaña por su ID.
     *
     * @param int $id
     * @return Campana|null
     */
    public function buscarPorId(int $id): ?Campana;

    /**
     * Crear o actualizar una campaña.
     *
     * @param CampanaRequest $request
     * @return Campana
     */
    public function crear(CampanaRequest $request): Campana;

    /**
     * Eliminar una campaña por su ID.
     *
     * @param int $id
     * @return void
     */
    public function eliminar(int $id): void;

    /**
     * Obtener todas las campañas.
     *
     * @return Collection
     */
    public function obtenerTodas(): Collection;
}
