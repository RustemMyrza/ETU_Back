<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfrastructureSliderResource extends JsonResource
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
        $imagesNoUrl = json_decode($this->images);

        foreach($imagesNoUrl as $item)
        {
            $images[] = url($item);
        }

        return [
            'title' => $this->getTitle->{$lang},
            'images' => $images
        ];
    }
}
