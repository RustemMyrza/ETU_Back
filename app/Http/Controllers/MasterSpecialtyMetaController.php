<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MastersSpecialty;
use App\Models\MasterSpecialtyMeta;

class MasterSpecialtyMetaController extends MetaDataByParentController
{
    protected $modelClass = MasterSpecialtyMeta::class;
    protected $parentModelClass = MastersSpecialty::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/mastersSpecialty/%d/meta';
    protected $backUrl = 'admin/mastersSpecialty';

    public function index($parentId)
    {
        return $this->getPage($parentId);
    }

    public function store(Request $request, $parentId)
    {
        return $this->saveData($request, $parentId);
    }
}
