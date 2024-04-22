<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BachelorSchoolEducatorResource extends JsonResource
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
            'professsion' => $this->getPosition ? $this->getPosition->{$lang} : '',
            'name' => $this->getName ? $this->getName->{$lang} : '',
            'image' => $this->image ? url($this->image) : ''
        ];
    }
}
