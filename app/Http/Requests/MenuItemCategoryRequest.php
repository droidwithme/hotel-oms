<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemCategoryRequest extends FormRequest
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
                    'category-name' => 'required',
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
            'category-name.required' => 'A category name is required' ,
        ];
    }
}
