<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DormitoryResource extends JsonResource
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
            'content' => $this->getContent ? $this->getContent->{$lang} : '',
            'dormitory_id' => $this->dormitory_id ? $this->dormitory_id : ''
        ];
    }
}
