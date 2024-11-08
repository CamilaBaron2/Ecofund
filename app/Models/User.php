<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'salt',
    ];

    protected $hidden = [
        'password',
        'salt',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Establecer la contraseña y el salt.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        // Generar un salt aleatorio
        $this->attributes['salt'] = bin2hex(random_bytes(16));

        // Definir un pepper estático
        $pepper = 'Ec07und'; // Cambia esto por tu propio pepper

        // Hash de la contraseña con el salt y el pepper
        $this->attributes['password'] = bcrypt($value . $this->attributes['salt'] . $pepper);

        // Logging para depuración
        Log::info('Salt generado: ' . $this->attributes['salt']);
        Log::info('Contraseña hasheada: ' . $this->attributes['password']);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Agrega otros métodos y relaciones que necesites para tu modelo.
}

