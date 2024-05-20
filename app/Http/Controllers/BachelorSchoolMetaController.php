<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSchoolMeta;
use App\Models\BachelorSchool;

class BachelorSchoolMetaController extends MetaDataByParentController
{
    protected $modelClass = BachelorSchoolMeta::class;
    protected $parentModelClass = BachelorSchool::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/bachelorSchool/%d/meta';
    protected $backUrl = 'admin/bachelorSchool/';

    public function index($parentId)
    {
        return $this->getPage($parentId);
    }

    public function store(Request $request, $parentId)
    {
        return $this->saveData($request, $parentId);
    }
}
