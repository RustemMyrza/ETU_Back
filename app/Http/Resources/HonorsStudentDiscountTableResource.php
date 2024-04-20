<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HonorsStudentDiscountTableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $lang = in_array($request->lang, ['ru', 'en', 'kz']) ? $request->lang : 'ru';

        $returnData = [
            'category' => $this->category ? $this->getCategory->{$lang} : '',
            'gpa' => $this->gpa,
            'amount' => $this->amount,
            'note' => $this->note ? $this->getNote->{$lang} : ''
        ];

        return $returnData;
    }
}
