<?php

namespace App\Domain\Campana;

use App\Models\Campana;
use App\Http\Requests\CampanaRequest;
use Illuminate\Database\Eloquent\Collection;

interface ICampanaService
{
    // Métodos para la base de datos
    public function buscarPorId(int $id): ?Campana;
    public function crear(CampanaRequest $request): Campana;
    public function eliminar(int $id): void;
    public function obtenerTodas(): Collection;

    // Métodos para la pila
    public function agregarCampanaPila(CampanaRequest $request): array;
    public function obtenerCampanaPila(): ?array;
    public function sacarCampanaPila(): ?array;
}
