<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSchoolSpecialtyMeta;
use App\Models\BachelorSchool;
use App\Models\BachelorSchoolSpecialty;

class BachelorSchoolSpecialtyMetaController extends Controller
{
    public function index($schoolId, $specialtyId)
    {
        $schoolData = BachelorSchool::findOrFail($schoolId);
        $specialtyData = BachelorSchoolSpecialty::where('school_id', $schoolId)->where('id', $specialtyId)->first();
        $viewName = 'metaData.index';
        $pageTitle = 'Метаданные "' . $specialtyData->getName->ru . '"';
        $pageHeader = 'Метаданные страница: "' . $schoolData->getName->ru . ' - ' . $specialtyData->getName->ru . '"';
        $redirectUrl = url('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/meta');
        $backUrl = url('admin/bachelorSchool/' . $schoolId . '/specialty/');
        $formAction = url($redirectUrl);
        $metaData = BachelorSchoolSpecialtyMeta::where('specialty_id', $specialtyId)->first();
        return view($viewName, compact([
            'metaData',
            'pageTitle',
            'pageHeader',
            'formAction',
            'backUrl'
        ]));
    }

    public function store(Request $request, $schoolId, $specialtyId)
    {
        $redirectUrl = url('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/meta');
        $requestData = $request->all();
        foreach($requestData as $key => $value)
        {
            if ($key != '_token' && $key != 'name' && $key != 'description')
            {
                if ($value != '')
                {
                    $keywords[] = $value; 
                }
            }
        }
        $metaData = BachelorSchoolSpecialtyMeta::where('specialty_id', $specialtyId)->first();
        if ($metaData) {
            $metaData->name = $requestData['name'];
            $metaData->description = $requestData['description'];
            $metaData->keyword = implode(', ', $keywords);
            $metaData->specialty_id = $specialtyId;
            $metaData->update();
        } else {
            $newMetaData = new BachelorSchoolSpecialtyMeta;
            $newMetaData->name = $requestData['name'];
            $newMetaData->description = $requestData['description'];
            $newMetaData->keyword = implode(', ', $keywords);
            $newMetaData->specialty_id = $specialtyId;
            $newMetaData->save();
        }

        return redirect($redirectUrl)->with('success', 'Изменения сохранены');
    }
}
