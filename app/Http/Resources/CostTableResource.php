<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CostTableResource extends JsonResource
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
        if ($this->type == 1)
        {
            $returnData = [
                'program' => $this->program ? $this->getProgram->{$lang} : '',
                'first' => $this->first,
                'second' => $this->second,
                'third' => $this->third,
                'fourth' => $this->fourth,
                'fifth' => $this->fifth,
                'total' => $this->total
            ];
        }
        else if ($this->type == 2)
        {
            $returnData = [
                'program' => $this->program ? $this->getProgram->{$lang} : '',
                'first' => $this->first,
                'second' => $this->second,
                'third' => $this->third,
                'fourth' => $this->fourth,
                'total' => $this->total
            ];
        }
        else
        {
            $returnData = [
                'program' => $this->program ? $this->getProgram->{$lang} : '',
                'first' => $this->first,
                'second' => $this->second,
                'total' => $this->total
            ];
        }
        return $returnData;
    }
}
