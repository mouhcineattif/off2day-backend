<?php

namespace App\Modules\Companies\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize()
    {
        // Add your real permission checks later
        return true;
    }

    public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'domain'    => 'nullable|string|max:255',
            'timezone'  => 'nullable|string|max:64',
            'locale'    => 'nullable|string|max:10',
            'status'    => 'nullable|in:active,suspended,trial,canceled',
        ];
    }
}
