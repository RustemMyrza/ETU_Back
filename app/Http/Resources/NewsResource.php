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
        $yearParam = in_array($request->year, ['2024', '2023', '2022', '2021']) ? $request->year : null;
        $monthParam = in_array($request->month, range(1, 12)) ? $request->month : null;
        $dayParam = in_array($request->day, range(1, 31)) ? $request->day : null;
        $dateArr = explode('-', $this->date);


        $params = [$yearParam, $monthParam, $dayParam];

        $emptyParamsCount = 0;

        foreach ($params as $item)
        {
            if ($item === null)
            {
                $emptyParamsCount += 1;
            }
        }

        if ($emptyParamsCount == 3)
        {
            return $news;
        }
        else if ($emptyParamsCount < 3)
        {
            $filledParamsCount = 3 - $emptyParamsCount;
            $correctParamsCount = 0;
            foreach ($params as $key => $value)
            {
                if ($value == $dateArr[$key])
                {
                    $correctParamsCount += 1;
                    if ($correctParamsCount == $filledParamsCount)
                    {
                        return $news;
                    }
                }
            }
        }
    }
}
