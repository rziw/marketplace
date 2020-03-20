<?php

namespace App\Http\Requests\Admin;

use App\Traits\Validation\HandleFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShopRequest extends FormRequest
{
    use HandleFailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|min:3|max:255',
            'sheba_number' => 'min:10|max:255|unique:shops,sheba_number,'.$this->shop->id,
            'address' => 'string|min:5|max:20000',
            'province' => 'string|min:3|max:60',
            'city' => 'string|min:3|max:60',
        ];
    }
}
