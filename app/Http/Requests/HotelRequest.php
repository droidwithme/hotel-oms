<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRequest extends FormRequest
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
            case 'PUT':
                return []; break;

            case 'POST':
                return [
                    'name' => 'required',
                    'email' => 'sometimes|nullable|email|unique:hotels,email',
                    'mobile' => 'required|digits_between:10,13|numeric|unique:hotels,mobile',
                    'address' => 'required',
                    'hotel_category' => 'required|numeric',
                    'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                    'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                    'password' => 'required|min:6',
                    'confirm-password' => 'required|min:6|same:password'
                ]; break;

            case 'PATCH':
                return [
                    'name' => 'required',
                    'email' => 'sometimes|nullable|email|'.Rule::unique('hotels')->ignore($this->id),
                    'mobile' => 'required|digits_between:10,13|numeric|'.Rule::unique('hotels')->ignore($this->id),
                    'address' => 'required',
                    'hotel_category' => 'required|numeric',
                    'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
                    'long' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
                    'password' => 'min:6|nullable',
                    'confirm-password' => 'min:6|same:password|nullable'
                ]; break;

            default :
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
            'name.required' => 'A name is required' ,

            'email.required' => 'An email address is required' ,
            'email.email' => 'The email address entered must be a valid email address (example@domain.com)' ,
            'email.unique' => 'The email address has already been taken',

            'mobile.required' => 'A mobile number is required' ,
            'mobile.digits_between' => 'Mobile must be of at least 10 - 13 digits' ,
            'mobile.numeric' => 'The mobile number entered must be of a numeric format ( 0-9 )',
            'mobile.unique' => 'The mobile number has already been taken',

            'address.required' => 'An address is required' ,

            'hotel_category.required' => 'A hotel category is required' ,
            'hotel_category.numeric' => 'The hotel category must be selected from the options' ,

            'lat.required' => 'A latitude is required',
            'lat.regex' => 'A valid latitude is required',
            'long.required' => 'A longitude is required',
            'long.regex' => 'A valid longitude is required',

            'password.required' => 'A password is required' ,
            'password.min' => 'The password entered must be of at least 6 characters',

            'confirm-password.required' => 'A confirm password is required',
            'confirm-password.min' => 'The confirm password entered must be of at least 6 characters',
            'confirm-password.same' => 'The confirm password entered must match the password',

        ];
    }
}
