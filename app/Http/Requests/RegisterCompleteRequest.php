<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompleteRequest extends FormRequest
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
            'name_family' => 'required',
            'name_fore' => 'required',
            'name_family_eng' => 'required',
            'name_family_fore' => 'required',
            'email' => 'required|email|unique:ho_account',
            'password' => 'required|confirmed|min:3',
            'day' => 'required|integer|between:1,31',
            'year' => 'required|integer|between:'.(date('Y') - 110).','.date('Y'),
            'birth' => 'required|date|before:-1 years',
            'tearms' => 'required',
        ];
    }

    /**
     * Validate request.
     *
     * @return void
     */
    public function validate()
    {
        $birth = $this->only('year', 'month', 'day');
        $birthday = implode('-', $birth);

        $this->merge(['birth' => $birthday]);

        return parent::validate();
    }

    public function messages()
    {
        return [];
    }
}
