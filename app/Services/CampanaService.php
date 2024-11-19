<?php

namespace App\Services;

use SplStack;

class CampanaService
{
    protected $pila;

    public function __construct()
    {
        // Inicializar la pila
        $this->pila = new SplStack();
    }

    // Método para agregar una campaña a la pila
    public function agregarCampana($campana)
    {
        $this->pila->push($campana);
    }

    // Método para quitar la última campaña de la pila
    public function quitarCampana()
    {
        if ($this->pila->isEmpty()) {
            return null;
        }

        return $this->pila->pop();
    }

    // Método para obtener la última campaña de la pila sin quitarla
    public function verUltimaCampana()
    {
        if ($this->pila->isEmpty()) {
            return null;
        }

        return $this->pila->top();
    }

    // Método para obtener el tamaño de la pila
    public function obtenerTamanoPila()
    {
        return $this->pila->count();
    }
}
