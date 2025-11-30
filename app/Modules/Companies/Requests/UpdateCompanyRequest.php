<?php

namespace App\Modules\Companies\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        // Add real authorization rules later
        return true;
    }

    public function rules()
    {
        return [
            'name'      => 'sometimes|required|string|max:255',
            'domain'    => 'nullable|string|max:255',
            'timezone'  => 'nullable|string|max:64',
            'locale'    => 'nullable|string|max:10',
            'status'    => 'nullable|in:active,suspended,trial,canceled',
        ];
    }
}
