<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreInvoiceRequest extends FormRequest
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
            'code' => 'required|string',
            'buyer_name' => 'required',
            'economical_number' => 'required',
            'national_number' => 'required',
            'need_no' => 'required',
            'postal_code' => 'required',
            'phone' => 'required|string|regex:/^09[0-9]{9}$/',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'کد الزامی است.',
            'buyer_name.required' => 'نام خریدار الزامی است.',
            'economical_number.required' => 'شماره اقتصادی الزامی است.',
            'economical_number.digits' => 'شماره اقتصادی باید 12 رقم باشد.',
            'national_number.required' => 'شناسه ملی الزامی است.',
            'national_number.digits' => 'شناسه ملی باید 10 رقم باشد.',
            'need_no.required' => 'شماره نیاز الزامی است.',
            'need_no.integer' => 'شماره نیاز باید یک عدد صحیح باشد.',
            'postal_code.required' => 'کد پستی الزامی است.',
            'postal_code.digits' => 'کد پستی باید 10 رقم باشد.',
            'phone.required' => 'شماره تلفن الزامی است.',
            'phone.regex' => 'فرمت شماره تلفن باید به صورت صحیح باشد.',
            'province.required' => 'استان الزامی است.',
            'city.required' => 'شهر الزامی است.',
            'address.required' => 'آدرس الزامی است.',
        ];
    }
}
