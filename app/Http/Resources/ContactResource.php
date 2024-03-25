<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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

        return [
            'id' => $this->id,
            'name' => $this->getTabName ? $this->getTabName->{$lang} : '',
            'address' => $this->address,
            'admissions_committee_num_1' => $this->admissions_committee_num_1,
            'admissions_committee_num_2' => $this->admissions_committee_num_2,
            'admissions_committee_mail' => $this->admissions_committee_mail,
            'rectors_reception_num' => $this->rectors_reception_num,
            'office_receptionist_num' => $this->office_receptionist_num,
            'tiktok_name' => $this->tiktok_name,
            'tiktok_link' => $this->tiktok_link,
            'instagram_link' => $this->instagram_link,
            'facebook_link' => $this->facebook_link,
            'youtube_link' => $this->youtube_link,
        ];
    }
}
