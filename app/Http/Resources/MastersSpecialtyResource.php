<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MasterSpecialtyPageDocument;
use App\Http\Resources\PageResource;
use App\Http\Resources\DocumentResource;
use League\CommonMark\Block\Element\Document;
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
        $specialtyDocuments = [];
        if ($this->getPage)
        {
            $pageApi = new stdClass;
            $documents = MasterSpecialtyPageDocument::query()->with(['getName'])->get();
            
            foreach ($documents as $item)
            {
                if ($item->specialty_id == $this->id)
                {
                    $specialtyDocuments[] = new DocumentResource($item);
                }
            }
            
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

            $specialtyDocuments ? $pageApi->documents = $specialtyDocuments : null;
            
            return [
                'id' => $this->id,
                'name' => $this->getName ? $this->getName->{$lang} : '',
                'image' => $this->image ? url($this->image) : '',
                'pageContent' => $pageApi
            ];
        }
        else
        {
            return [
                'id' => $this->id,
                'name' => $this->getName ? $this->getName->{$lang} : '',
                'image' => $this->image ? url($this->image) : '',
                'pageContent' => ''
            ];
        }
    }
}
