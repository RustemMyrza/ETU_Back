<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MonthResource;
use App\Http\Resources\SortResource;
use App\Models\Translate;

class FiltersResource extends JsonResource
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
        $months = Translate::whereBetween('id', [182, 193])->get();
        $sort = Translate::whereBetween('id', [180, 181])->get();
        $childData[178] = MonthResource::collection($months);
        $childData[177] = [2024, 2023, 2022, 2021];
        $childData[176] = SortResource::collection($sort);
        $childData[179] = range(1, 31);

        return[
            'id' => $this->id,
            'name' => $this->{$lang},
            'child' => array_key_exists($this->id, $childData) ? $childData[$this->id] : null
        ];
    }
}
