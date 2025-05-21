<?php

namespace App\Http\Requests;

use App\Rules\ValidTicketPriority;
use App\Rules\ValidTicketStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->ticket);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|min:10',
            'priority' => ['sometimes', 'required', new ValidTicketPriority()],
            'status' => ['sometimes', 'required', new ValidTicketStatus()],
            'category_id' => 'sometimes|required|exists:categories,id',
            'assigned_to' => 'sometimes|nullable|exists:users,id',
        ];
    }
}
