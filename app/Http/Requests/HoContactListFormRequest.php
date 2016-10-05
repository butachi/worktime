<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Route;

class HoContactListFormRequest extends Request
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
        // switch case
        switch (Route::getCurrentRoute()->getName()) {
            case 'contact.store':
                return [
                    'name' => 'required|string|max:50',
                    'email' => 'required|email',
                    'contents' => 'required|string|max:250',
                ];
                break;
            default:        
                return [];
                break;
        }
    }
}
