<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SolicitudRecibidaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreCampana;
    public $descripcion;
    public $imagenes;

    public function __construct($nombreCampana, $descripcion, $imagenes = [])
    {
        $this->nombreCampana = $nombreCampana;
        $this->descripcion = $descripcion;
        $this->imagenes = $imagenes;
    }

    public function build()
    {
        $correo = $this->view('emails.solicitud_recibida')
                       ->subject($this->nombreCampana)
                       ->with([
                           'descripcion' => $this->descripcion,
                       ]);

        // Adjuntar imágenes si están presentes
        foreach ($this->imagenes as $imagen) {
            $correo->attach($imagen->getRealPath(), [
                'as' => $imagen->getClientOriginalName(),
                'mime' => $imagen->getMimeType(),
            ]);
        }

        return $correo;
    }
}
