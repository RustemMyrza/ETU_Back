<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PageResource;
use App\Models\Translate;
use stdClass;

class NewsResource extends JsonResource
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
        $slider = $this->getSlider ? $this->getSlider : null;
        if (isset($slider->images))
        {
            foreach(json_decode($slider->images) as $item)
            {
                $sliderImages[] = url($item);
            }
        }


        $blocks = PageResource::collection($this->getChild);
        $content = new stdClass;
        $content->title = $this->getName ? $this->getName->{$lang} : '';
        $content->date = $this->date;
        $content->slider = isset($sliderImages) ? $sliderImages : null;
        $content->blocks = $blocks;

        $news = [
            'id' => $this->id,
            'name' => $this->getName ? $this->getName->{$lang} : '',
            'date' => $this->date,
            'image' => $this->background_image ? url($this->background_image) : '',
            'child' => $this->getChild ? $content : []
        ];

        return $news;
    }
}
