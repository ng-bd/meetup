<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendeeRequest extends FormRequest
{
    /**
     * @var int
     */
    protected $attendeeId;

    public function __construct()
    {
        if (!empty(\Route::current()->parameters['id'])) {
            $this->attendeeId = intval(\Route::current()->parameters['id']);
        }
    }

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
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'email' => 'required|unique:attendees,email',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:11|unique:attendees,mobile',
            'misc.tshirt' => 'required',
            'profession' => 'required',
            'social_profile_url' => 'required|url'
        ];

        if($this->attendeeId) {
            unset($rules['misc.tshirt']);
            foreach (['email', 'mobile'] as $item) {
                $rules[$item] .= ','.$this->attendeeId;
            }
        }


        return  $rules;
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
