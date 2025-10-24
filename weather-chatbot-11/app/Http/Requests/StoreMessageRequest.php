<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'conversation_id' => ['nullable','exists:conversations,id'],
            'text' => ['required','string','max:2000'],
            'location.lat' => ['nullable','numeric'],
            'location.lng' => ['nullable','numeric'],
        ];
    }
}
