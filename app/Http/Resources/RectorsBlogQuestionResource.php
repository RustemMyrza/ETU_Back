<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RectorsBlogQuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->answer)
        {
            return [
                'name' => $this->name . ' ' .$this->surname,
                'phone' => $this->phone,
                'email' => $this->email,
                'date' => $this->updated_at,
                'question' => $this->question,
                'answer' => $this->answer,
            ];
        }
    }
}
