<?php
namespace App\Modules\Invites\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInviteRequest extends FormRequest
{
    public function authorize()
    {
        // Only allow company admins (Policy should enforce too)
        return $this->user() && $this->user()->role === 'company_admin';
    }

    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|in:employee,company_admin',
            'expires_days' => 'nullable|integer|min:1|max:365',
        ];
    }
}
