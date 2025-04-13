<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'priority' => 'required',
            'employee_description' => 'required',
            'file' => 'nullable',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'وارد کردن عنوان الزامی است.',
            'title.max' => 'عنوان نباید بیشتر از :max کاراکتر باشد.',
            'type.required' => 'انتخاب نوع درخواست الزامی است.',
            'type.in' => 'نوع درخواست انتخاب‌شده معتبر نیست.',
            'priority.required' => 'انتخاب اولویت الزامی است.',
            'priority.in' => 'اولویت انتخاب‌شده معتبر نیست.',
            'employee_description.string' => 'توضیحات باید به‌صورت متن وارد شود.',
            'file.file' => 'فایل ارسالی نامعتبر است.',
            'file.mimes' => 'فرمت فایل باید یکی از موارد زیر باشد: jpg, jpeg, png, pdf, doc, docx.',
            'file.max' => 'حجم فایل نباید بیشتر از 2 مگابایت باشد.',
        ];
    }
}
