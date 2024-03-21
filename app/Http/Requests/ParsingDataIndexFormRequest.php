<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ParsingDataIndexFormRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'nullable'],
            'month' => ['string', 'nullable']
        ];
    }


    /**
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator): array
    {
        throw new HttpResponseException(response()->json(
            [
                'success' => false,
                'message' => 'Ошибка',
                'status' => 422,
                'error' => $validator->errors(),
                'data' => [],
            ])
        );
    }

}
