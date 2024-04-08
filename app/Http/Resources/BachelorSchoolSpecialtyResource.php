<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;
class BachelorSchoolSpecialtyResource extends JsonResource
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
        $blocks = new stdClass;
        foreach ($this->getPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $blocks->title = new PageResource($value);
                    break;
                case 1:
                    $blocks->aboutProgram = new PageResource($value);
                    break;
                case 2:
                    $blocks->tasks = new PageResource($value);
                    break;
                case 3:
                    $blocks->target = new PageResource($value);
                    break;
                case 4:
                    $blocks->competencies_and_results = new PageResource($value);
                    break;
                case 5:
                    $blocks->disciplines = new PageResource($value);
                    break;
                case 6:
                    $blocks->career = new PageResource($value);
                    break;
                case 7:
                    $blocks->key_disciplines = new PageResource($value);
                    break;
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->getName ? $this->getName->{$lang} : '',
            'items' => $this->getPage ? $blocks : ''
        ];
    }
}
