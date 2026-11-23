<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'nombre' => ['required', 'string', 'max:255',
                Rule::unique('products', 'nombre')->ignore($product?->id)],
            'descripcion' => ['required', 'string'],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'precio' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'existencias' => ['required', 'integer', 'min:0'],
            'estado' => ['required', Rule::in(['disponible', 'agotado'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'category_id.required' => 'Selecciona una categoría.',
            'category_id.exists' => 'La categoría seleccionada no existe en el catálogo.',
            'precio.numeric' => 'El precio debe ser numérico.',
            'precio.gt' => 'El precio debe ser mayor que cero.',
            'existencias.integer' => 'Las existencias deben ser un número entero.',
            'existencias.min' => 'Las existencias no pueden ser negativas.',
            'estado.in' => 'Selecciona un estado válido: Disponible o Agotado.',
        ];
    }
}