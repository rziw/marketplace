<?php

namespace App\Http\Requests\User;

use App\Traits\Validation\HandleFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user->id,
            'family'  => 'required|string|min:3|max:255',
            'mobile_number' => 'required|string|min:11|max:12',
            'home_number' => 'required|string|min:4|max:50',
            'address' => 'required|string|min:5|max:20000',
            'province' => 'required|string|min:3|max:60',
            'city' => 'required|string|min:3|max:60',
            ];
    }
}
