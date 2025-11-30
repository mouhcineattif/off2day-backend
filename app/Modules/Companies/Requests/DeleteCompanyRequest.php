<?php

namespace App\Modules\Companies\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCompanyRequest extends FormRequest
{
    public function authorize()
    {
        // Add permission logic here later
        return true;
    }

    public function rules()
    {
        return [
            // Nothing to validate for deletion,
            // but the class exists so you can expand later.
        ];
    }
}
