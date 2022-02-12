<?php

namespace App\Http\Requests;

use App\Services\PercentageService;
use Illuminate\Foundation\Http\FormRequest;
use SebastianBergmann\CodeCoverage\Percentage;

class ConfigRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'value' =>['required','numeric']
        ];
    }

    public function prepareForValidation(): void
    {
        $value = (new PercentageService())->convertToPercentage($this->value);

        $this->merge([
            'value' => $value,
        ]);
    }
}
