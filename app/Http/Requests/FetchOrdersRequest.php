<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FetchOrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required',
        ];
    }
}

