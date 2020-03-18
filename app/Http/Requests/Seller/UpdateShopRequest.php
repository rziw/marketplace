<?php

namespace App\Http\Requests\Seller;

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
            'name' => 'required|string|min:3|max:255',
            'sheba_number' => 'required|min:10|max:255|unique:shops,sheba_number,'.$this->shop->id,
            'address' => 'required|string|min:5|max:20000',
            'product_type' => 'required|string|min:3|max:255',
        ];
    }
}
