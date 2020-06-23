<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            case 'PATCH':
                return [
                    'product-category-id' => 'required|numeric',
                    'product-name' => 'sometimes|required',
                    'product-price' => 'required|numeric',
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
            'product-category-id.required' => 'A product category is required' ,
            'product-category-id.numeric' => 'The product category must be numeric' ,
            'product-name.required' => 'A product name is required' ,

            'product-price.required' => 'A product price is required' ,
            'product-price.numeric' => 'The product price must be numeric' ,
        ];
    }
}
