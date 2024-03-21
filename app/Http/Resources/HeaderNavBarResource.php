<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AboutUniversityPageResource;
use App\Http\Resources\EnrollmentPageResource;
use App\Http\Resources\StudentsPageResource;
use App\Http\Resources\SchoolsPageResource;
use App\Http\Resources\SciencePageResource;
use App\Models\AboutUniversityPage;
use App\Models\EnrollmentPage;
use App\Models\StudentsPage;
use App\Models\SchoolsPage;
use App\Models\SciencePage;

class HeaderNavBarResource extends JsonResource
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

        $aboutUniversityPageData = AboutUniversityPage::query()->with('getName')->get();
        $aboutUniversityPageResourceData = AboutUniversityPageResource::collection($aboutUniversityPageData);

        // $data = AboutUniversityPage::first()->parent_id;
        // dd($data);

        $enrollmentPageData = EnrollmentPage::query()->with('getName')->get();
        $enrollmentPageResourceData = EnrollmentPageResource::collection($enrollmentPageData);

        $studentsPageData = StudentsPage::query()->with('getName')->get();
        $studentsPageResourceData = StudentsPageResource::collection($studentsPageData);

        $schoolsPageData = SchoolsPage::query()->with('getName')->get();
        $schoolsPageResourceData = SchoolsPageResource::collection($schoolsPageData);

        $sciencePageData = SciencePage::query()->with('getName')->get();
        $sciencePageResourceData = SciencePageResource::collection($sciencePageData);
        
        // $aboutUniversityPageResourceData2 = AboutUniversityPageResource::collection($aboutUniversityPageData2);


        $resourcesData = [
            AboutUniversityPage::first()->parent_id => $aboutUniversityPageResourceData,
            EnrollmentPage::first()->parent_id => $enrollmentPageResourceData,
            StudentsPage::first()->parent_id => $studentsPageResourceData,
            SchoolsPage::first()->parent_id => $schoolsPageResourceData,
            SciencePage::first()->parent_id => $sciencePageResourceData
        ];

        // dd($resourcesData);

        $structuredData = [
            'id' => $this->id,
            'name' => $this->getName ? $this->getName->{$lang} : '',
            'child' => $resourcesData[$this->id]
        ];
        // foreach ($aboutUniversityPageResourceData->collection as $item) {
        //     // Получаем значение parent_id с использованием атрибута доступа getParentIdAttribute()
        //     $parentId = $item->getParentId;
        
        //     dd($parentId); // Проверяем, что значение parent_id не равно null
        // }
        
        // dd($aboutUniversityPageResourceData->collection);
        // $arr = (array) $aboutUniversityPageResourceData;
        // // dd($aboutUniversityPageResourceData);
        // // foreach ($arr as $item) {
        // //     // $collects = $item->collects; // Получаем информацию о классе, используемом для сбора ресурсов
        // //     // $collection = $item->collection; // Получаем коллекцию данных
        // //     // $resource[] = $item->resource; // Получаем ресурсы
        // //     // $with = $item->with; // Получаем дополнительные данные или связанные ресурсы
        // //     // $additional = $item->additional; // Получаем дополнительные данные
        // //     echo '<pre>';
        // //     print_r($item);
        // //     echo '</pre>';
        // //     // Далее можно работать с полученными данными
        // // }        
        // // return $resource;
        // foreach ($arr['collection'] as $item)
        // {
        //     $arrItem = (array) $item;
        //     return $arrItem;
        // }
        // return $arr['collection'];
        return $structuredData;
    }
}
