<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MastersSpecialtyPage;
use App\Http\Resources\PageResource;
use stdClass;

class MastersSpecialtyResource extends JsonResource
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
        if ($this->getPage)
        {
            $pageApi = new stdClass;
            foreach ($this->getPage as $key => $value)
            {
                switch ($key)
                {
                    case 0:
                        $pageApi->title = new PageResource($value);
                        break;
                    case 1:
                        $pageApi->aboutProgram = new PageResource($value);
                        break;
                    case 2:
                        $pageApi->programTask = new PageResource($value);
                        break;
                    case 3:
                        $pageApi->programGoal = new PageResource($value);
                        break;
                    case 4:
                        $pageApi->listCompetencies = new PageResource($value);
                        break;
                    case 5:
                        $pageApi->majorDisciplines = new PageResource($value);
                        break;
                    case 6:
                        $pageApi->careerOpportunities = new PageResource($value);
                        break;
                    case 7:
                        $pageApi->keyDisciplines = new PageResource($value);
                        break;
                }
            }

            return [
                'id' => $this->id,
                'name' => $this->getName ? $this->getName->{$lang} : '',
                'pageContent' => $pageApi
            ];
        }
        else
        {
            return [
                'id' => $this->id,
                'name' => $this->getName ? $this->getName->{$lang} : '',
                'pageContent' => ''
            ];
        }
    }
}
