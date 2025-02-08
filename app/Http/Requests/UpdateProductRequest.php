<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'title' => 'required',
            'code' => 'required',
            'sku' => 'required|unique:products,sku,'.$this->product->id,
            'product_barcode' => ['nullable', Rule::unique('products', 'product_barcode')->ignore($this->product->id)],
            'brand_id' => 'required',
            'category' => 'required',
            'single_price' => 'required',
        ];
    }
}
