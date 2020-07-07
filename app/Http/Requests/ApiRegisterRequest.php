<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRegisterRequest extends FormRequest
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
        $method = $this->method();

        switch ($method) {
            case 'GET':
            case 'DELETE':
            case 'PATCH':
            case 'PUT':
                return [];
                break;

            case 'POST':
                return [
                    'name' => 'required',
                    'email' => 'sometimes|required|email',
                    'mobile' => 'required|numeric|digits_between:10,13',
                    'password' => 'required|min:6',
                    'address' => 'required',
                    'lat' => 'sometimes|required',
                    'long' => 'sometimes|required',
                    'fcm_token' => 'sometimes|required',
                ];
                break;
            default:
                break;
        }
    }

    /**
     * Modify the returned validation error messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'A name is required',
            'email.required' => 'A email is required',
            'email.email' => 'A valid email is required',
            'mobile.required' => 'A mobile number is required',
            'mobile.digits_between' => 'Mobile must be of at least 10 - 13 digits',
            'mobile.numeric' => 'The mobile number entered must be of a numeric format ( 0-9 )',
            'password.required' => 'A password is required',
            'address.required' => 'An address is required',
            'password.min' => 'Password must be of at least 6 characters',
            'lat.required' => 'Latitude is required',
            'long.required' => 'Longitude is required',
            'fcm_token.required' => 'Fcm token is required',
        ];
    }
}
