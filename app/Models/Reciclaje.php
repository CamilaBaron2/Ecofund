<?php

// app/Models/Reciclaje.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reciclaje extends Model
{
    use HasFactory;

    // Especificar la tabla si el nombre no sigue la convención plural
    protected $table = 'reciclajes';

    // Atributos que pueden ser asignados masivamente
    protected $fillable = [
        'titulo',
        'descripcion',
        'ubicacion',
        'fecha_inicio',
    ];
}
