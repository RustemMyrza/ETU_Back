<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScienceInnovationPageResource extends JsonResource
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
            'title' => $this->getTitle ? $this->getTitle->{$lang} : '',
            'content' => $this->getContent ? $this->getContent->{$lang} : '',
            'image' => $this->image ? url($this->image) : ''
        ];
    }
}
