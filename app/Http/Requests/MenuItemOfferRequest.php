<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemOfferRequest extends FormRequest
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
                return [];
                break;

            case 'POST':
            case 'PATCH':
                return [
                    'offer-name' => 'required',
                    'offer-description' => 'required',
                    'offer-type' => 'required|numeric|in:0,1,2',
                    'offer-type-detail' => 'required|numeric',
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
            'offer-name.required' => 'An offer name is required',
            'offer-description.required' => 'An offer description is required',
            'offer-type.required' => 'An offer type is required',
            'offer-type.numeric' => 'The offer type must be numeric',
            'offer-type.in' => 'The offer type must be selected',
            'offer-type-detail.required' => 'An offer type detail is required',
            'offer-type-detail.numeric' => 'The offer type detail must be numeric',
        ];
    }
}
