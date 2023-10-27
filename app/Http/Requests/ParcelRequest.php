<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParcelRequest extends FormRequest
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
            'origin_name' => 'required|string|max:255',
            'origin_mobile' => 'required|string|size:11',
            'origin_address' => 'required|string',
            'origin_latitude' => 'required|numeric|min:-90|max:90',
            'origin_longitude' => 'required|numeric|min:-180|max:180',
            'destination_name' => 'required|string|max:255',
            'destination_mobile' => 'required|string|size:11',
            'destination_address' => 'required|string',
            'destination_latitude' => 'required|numeric|min:-90|max:90',
            'destination_longitude' => 'required|numeric|min:-180|max:180',
        ];
    }
}
