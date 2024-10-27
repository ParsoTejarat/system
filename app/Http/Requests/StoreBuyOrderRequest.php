<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuyOrderRequest extends FormRequest
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
            'customer_id' => 'required',
            'order' => 'required|exists:orders,code',
            'products' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'products.required' => 'وارد کردن کالاهای سفارش خرید الزامی است',
            'order.required' => 'شناسه سفارش را وارد کنید.',
            'order.exists' => 'این شناسه معتبر نیست.',
        ];
    }
}
