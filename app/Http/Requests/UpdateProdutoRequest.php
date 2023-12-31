<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdutoRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|min: 2|unique:produtos,nome,' . $this->route('produto') . ',id|required',
            'descricao'=> 'required|min:2',
            'preco'=> 'decimal:2|required',
            'estoque'=> 'integer|required',
            'tipo_id'=> 'required|exists:tipos,id',
            //
        ];
    }

    
}
