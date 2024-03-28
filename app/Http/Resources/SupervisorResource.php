<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorResource extends JsonResource
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
            'name' => $this->getName ? $this->getName->{$lang} : '',
            'position' => $this->getPosition ? $this->getPosition->{$lang} : '',
            'email' => $this->email,
            'phone' => $this->phone,
            'position' => $this->getAddress ? $this->getAddress->{$lang} : '',
            'image' => $this->image
        ];
    }
}
