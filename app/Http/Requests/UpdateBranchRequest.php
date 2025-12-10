<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required',
            'name' => 'required',
            'unit_no' => 'required',
            'lease_code' => 'required',
        ];
    }
}

