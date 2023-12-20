<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:3', 'max:100'],
            'description' => ['required'],
            'remind_at' => ['required'],
            'event_at' => ['required'],
            'user_id' => ['nullable']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => auth('sanctum')->user()->id,
            'remind_at' => date('Y-m-d h:i:s', $this->remind_at),
            'event_at' => date('Y-m-d h:i:s', $this->event_at)
        ]);
    }
}
