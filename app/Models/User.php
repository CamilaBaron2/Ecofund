<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'salt',
    ];

    /**
     * Los atributos que deberían ser ocultos para los arreglos.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'salt',
        'remember_token',
    ];

    /**
     * Los atributos que deberían ser convertidos a tipos nativos.
     *
     * @var array
     */
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

        // Hash de la contraseña con el salt
        $this->attributes['password'] = bcrypt($value . $this->attributes['salt']);
    }

    // Agrega otros métodos y relaciones que necesites para tu modelo.
}
