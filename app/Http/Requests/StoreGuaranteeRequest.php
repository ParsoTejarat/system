<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuaranteeRequest extends FormRequest
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
            'product' => 'required|exists:products,id',
            'serial_number' => 'required|unique:guarantees,serial_number',
            'product_identifier' => 'required',
            'importing_company' => 'required',
            'period' => 'required|in:12,18,24',
        ];
    }
    public function messages()
    {
        return [
            'product.required' => 'لطفا محصول را انتخاب کنید.',
            'product.exists' => 'محصول انتخابی معتبر نمی‌باشد.',
            'serial_number.required' => 'لطفا شماره سریال را وارد کنید.',
            'importing_company.required' => 'شرکت وارد کننده این محصول را وارد کنید',
            'serial_number.unique' => 'شماره سریال قبلاً ثبت شده است.',
            'serial_number.string' => 'شماره سریال باید یک رشته متنی باشد.',
            'serial_number.max' => 'شماره سریال نباید بیشتر از 255 کاراکتر باشد.',
            'product_identifier.required' => 'لطفا شناسه محصول را وارد کنید.',
            'product_identifier.string' => 'شناسه محصول باید یک رشته متنی باشد.',
            'product_identifier.max' => 'شناسه محصول نباید بیشتر از 255 کاراکتر باشد.',
            'tracking_code.string' => 'شناسه رهگیری باید یک رشته متنی باشد.',
            'tracking_code.max' => 'شناسه رهگیری نباید بیشتر از 255 کاراکتر باشد.',
            'period.required' => 'لطفا مدت گارانتی را انتخاب کنید.',
            'period.in' => 'مدت گارانتی باید یکی از مقادیر 12، 18 یا 24 ماه باشد.',
        ];
    }
}
