<?php

// app/Services/ReciclajeQueueService.php
namespace App\Services;

use App\Models\Reciclaje;
use SplQueue;
use Illuminate\Support\Facades\Log;

class ReciclajeService
{
    protected $queue;

    public function __construct()
    {
        $this->queue = new SplQueue();
    }

    /**
     * Agregar una campaña a la cola y a la base de datos
     *
     * @param array $reciclajeData
     * @return void
     */
    public function agregarCampana(array $reciclajeData)
    {
        // Validamos que los datos sean correctos antes de agregar a la cola
        $this->queue->enqueue($reciclajeData);

        // Guardamos la campaña en la base de datos
        $reciclaje = Reciclaje::create($reciclajeData);

        if ($reciclaje) {
            Log::info('Campaña guardada en la base de datos', ['titulo' => $reciclajeData['titulo']]);
        } else {
            Log::error('Error al guardar la campaña en la base de datos', ['titulo' => $reciclajeData['titulo']]);
        }
    }

    /**
     * Procesar la cola de reciclaje
     *
     * @return mixed
     */
    public function procesarCola()
    {
        if ($this->queue->isEmpty()) {
            Log::warning('No hay campañas en la cola para procesar');
            return ['message' => 'No hay campañas en la cola para procesar'];
        }

        $campana = $this->queue->dequeue();

        // Aquí puedes hacer algo más con la campaña, como actualizarla en la base de datos si es necesario
        Log::info('Procesando campaña', ['titulo' => $campana['titulo']]);

        return $campana;
    }
}
