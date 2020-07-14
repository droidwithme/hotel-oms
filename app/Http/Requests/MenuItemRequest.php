<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
                    'item-category' => 'required',
                    'item-name' => 'required',
                    'item-price' => 'required|numeric',
                ]; break;

            case 'PATCH':
                return [
                    'item-category' => 'required',
                    'item-name' => 'required',
                    'item-price' => 'required|numeric',
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
            'item-category.required' => 'An item category must be selected' ,
            'item-name.required' => 'An item name is required' ,
            'item-price.required' => 'An item price is required' ,
        ];
    }
}
