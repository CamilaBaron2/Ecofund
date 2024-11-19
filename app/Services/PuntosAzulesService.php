<?php

namespace App\Services;

use App\Models\PuntosAzules;
use Illuminate\Support\Facades\Log;

class PuntosAzulesService
{
    /**
     * Agregar una campaña de puntos azules a la base de datos.
     *
     * @param array $data
     */
    public function agregarCampana(array $data)
    {
        Log::info('Iniciando el proceso de agregar campaña de puntos azules', ['titulo' => $data['titulo']]);

        // Guardar la campaña en la base de datos
        $puntosAzules = PuntosAzules::create($data);

        Log::info('Campaña de puntos azules guardada en la base de datos', ['id' => $puntosAzules->id]);
    }
}
