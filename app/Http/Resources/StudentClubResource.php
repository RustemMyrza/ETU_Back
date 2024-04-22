<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentClubResource extends JsonResource
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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->getDescription ? $this->getDescription->{$lang} : '',
            'logo' => $this->logo ? url($this->logo) : ''
        ];
    }
}
