<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampanaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:50', // Requiere un título de tipo string con máximo 255 caracteres
            'descripcion' => 'required|string', // Requiere una descripción de tipo string
            'ubicacion' => 'required|string|max:100', // Requiere una ubicación de tipo string con máximo 255 caracteres
            'fecha_inicio' => 'required|date', // Requiere una fecha de inicio en formato de fecha válida
        ];
    }

    /**
     * Obtener los mensajes de error personalizados.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título de la campaña es obligatorio.',
            'descripcion.required' => 'La descripción de la campaña es obligatoria.',
            'ubicacion.required' => 'La ubicación de la campaña es obligatoria.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
        ];
    }
}
