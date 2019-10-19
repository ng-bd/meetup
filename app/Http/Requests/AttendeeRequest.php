<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendeeRequest extends FormRequest
{
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
            'name' => 'required',
            'email' => 'required|unique:attendees,email',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|unique:attendees,mobile',
            'misc.tshirt' => 'required',
            'profession' => 'required',
            'social_profile_url' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'misc.tshirt' => 't-shirt',
        ];
    }
}
