<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrandsRequests extends FormRequest
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
            'name' => 'required',
            'name_en' => 'required',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'نام برند الزامی است.',
            'name_en.required' => 'نام انگلیسی برند الزامی است.',
            'categories.required' => 'دسته‌بندی‌ها الزامی هستند.',
            'categories.*.exists' => 'دسته‌بندی انتخاب شده معتبر نیست.',
        ];
    }
}
