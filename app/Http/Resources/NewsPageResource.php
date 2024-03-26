<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FiltersResource;
use App\Models\Translate;

class NewsPageResource extends JsonResource
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
        $filterNames = Translate::whereBetween('id', [176, 179])->get();
        $filterNames = FiltersResource::collection($filterNames);

        // return $filterNames;
        return [
            'title' => $this->{$lang},
            'filters' => $filterNames
        ];
    }
}
