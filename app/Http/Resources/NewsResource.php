<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Translate;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    private function translate($id, $lang)
    {
        return Translate::findOrFail($id)->$lang;
    } 

    public function toArray($request)
    {
        // dd($request);
        $lang = in_array($request->lang, ['ru', 'en', 'kz']) ? $request->lang : 'ru';
        $news = [
            'id' => $this->id,
            'name' => $this->getName ? $this->getName->{$lang} : '',
            'date' => $this->date,
            'image' => $this->background_image ? url($this->background_image) : '',
            'news_contents' => $this->getChild->map(function ($item) use ($lang) {
                return [
                    'id' => $item->id,
                    'title' => $this->translate($item->title, $lang),
                    'content' => $this->translate($item->content, $lang),
                    'image' => $item->image,
                    'parent_id' => $item->parent_id,
                ];
            }),
        ];

        return $news;
    }
}
