<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarketplaceRequest extends FormRequest
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
            'nome' => 'required|min: 2|unique:marketplaces,nome,' 
            . $this->route('marketplace') . ',id|required',
            'descricao'=> 'required|min:2',
            'url' => 'min:2|unique:marketplaces,url,' 
            . $this->route('marketplace') . ',id|required',
           
        ];
    }
}
