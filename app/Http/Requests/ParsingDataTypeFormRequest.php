<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ParsingDataTypeFormRequest extends FormRequest
{
    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            '*.title' => ['string', 'required'],
            '*.months' => ['array', 'required']
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
