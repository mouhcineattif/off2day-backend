<?php
namespace App\Modules\Invites\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcceptInviteRequest extends FormRequest
{
    public function authorize()
    {
        return true; // token based, keep public
    }

    public function rules()
    {
        return [
            'token' => 'required|string',
            'full_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'job_title' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
        ];
    }
}
