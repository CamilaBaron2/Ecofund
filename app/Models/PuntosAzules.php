<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntosAzules extends Model
{
    use HasFactory;

    protected $table = 'puntos_azules';

    protected $fillable = [
        'titulo',
        'descripcion',
        'ubicacion',
        'fecha_inicio',
    ];
}
