<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'type' => ['required', 'in:admin,student,library_staff,faculty_staff,student_activity_coordinator'],
            'faculty_id' => ['nullable', 'string', 'max:255'],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', Password::defaults()];
        } elseif ($this->isMethod('PUT')) {
            $rules['password'] = ['nullable', Password::defaults()];
            $rules['email'] = ['required', 'email', 'max:255', 'unique:users,email,' . $this->user->id];
        }

        return $rules;
    }
}
