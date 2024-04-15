<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\BachelorSchoolSpecialtyResource;
use App\Http\Resources\BachelorSchoolEducatorResource;
use stdClass;

class BachelorSchoolResource extends JsonResource
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
        $educators = [];
        $specialties = [];

        foreach ($this->getPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $blocks->pageTitle = new PageResource($value);
                    break;
                case 1:
                    $blocks->aboutBlock = new PageResource($value);
                    break;
                case 2:
                    $blocks->programsTitle = new PageResource($value);
                    break;
                case 3:
                    $blocks->educatorsTitle = new PageResource($value);
                    break;
            }
        }

        foreach ($this->getSpecialties as $key => $value)
        {
            $specialties[] = new BachelorSchoolSpecialtyResource($value);
        }

        foreach ($this->getEducators as $key => $value)
        {
            $educators[] = new BachelorSchoolEducatorResource($value);
        }

        return [
            'id' => $this->id,
            'title' => $this->getName ? $this->getName->{$lang} : '',
            'icon' => $this->image ? $this->image : '',
            'block' => $this->getPage ? $blocks : '',
            'program' => $this->getSpecialties ? $specialties : '',
            'teachingStaff' => $this->getEducators ? $educators : ''
        ];
    }
}