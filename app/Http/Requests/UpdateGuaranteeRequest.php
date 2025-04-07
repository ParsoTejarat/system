<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuaranteeRequest extends FormRequest
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


    public function rules()
    {
        return [
            'product' => ['required', 'exists:products,id'],
            'serial_number' => [
                'required',
                Rule::unique('guarantees', 'serial_number')->ignore($this->guarantee->id),
            ],
            'product_identifier' => ['required'],
            'tracking_code' => ['nullable'],
            'importing_company' => ['nullable'],
            'period' => ['required', Rule::in(array_keys(\App\Models\Guarantee::PERIOD))],
            'status' => ['required', Rule::in(array_keys(\App\Models\Guarantee::STATUS))],
            'expire_time' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'product.required' => 'انتخاب محصول الزامی است.',
            'product.exists' => 'محصول انتخاب‌شده معتبر نیست.',

            'serial_number.required' => 'وارد کردن شماره سریال الزامی است.',
            'serial_number.unique' => 'این شماره سریال قبلاً ثبت شده است.',

            'product_identifier.required' => 'وارد کردن شناسه کالا الزامی است.',

            'tracking_code.string' => 'شناسه رهگیری باید به صورت متن وارد شود.',
            'tracking_code.max' => 'شناسه رهگیری نباید بیش از ۲۵۵ کاراکتر باشد.',

            'importing_company.string' => 'نام شرکت واردکننده باید به صورت متن وارد شود.',
            'importing_company.max' => 'نام شرکت واردکننده نباید بیش از ۲۵۵ کاراکتر باشد.',

            'period.required' => 'انتخاب مدت گارانتی الزامی است.',
            'period.in' => 'مدت گارانتی انتخاب‌شده معتبر نیست.',

            'status.required' => 'انتخاب وضعیت الزامی است.',
            'status.in' => 'وضعیت انتخاب‌شده معتبر نیست.',

            'date.required' => 'وارد کردن تاریخ انقضاء الزامی است.',
            'date.date_format' => 'فرمت تاریخ انقضاء باید به صورت YYYY/MM/DD باشد.',
        ];
    }

}
