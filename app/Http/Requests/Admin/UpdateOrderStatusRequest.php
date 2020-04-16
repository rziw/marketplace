<?php

namespace App\Http\Requests\Admin;

use App\Enums\OrderStatus;
use App\Traits\Validation\HandleFailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
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
            'status' => 'required|in:' . implode(",", array_slice(OrderStatus::getKeys(), 2)),
        ];
    }
}
