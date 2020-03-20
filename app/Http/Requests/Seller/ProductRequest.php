<?php

namespace App\Http\Requests\Seller;

use App\Traits\Validation\HandleFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'name' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:2000',
            'color' => 'required|min:2|max:255',
            'count' => 'required|integer|min:0',
            'price' => 'required|integer|min:100',
            'has_guarantee' => 'sometimes|required|boolean',
            'guarantee_description' => 'required_if:has_guarantee,1',
        ];
    }
}
