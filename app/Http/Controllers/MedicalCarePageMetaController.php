<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class MedicalCarePageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/medicalCarePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Медицинское обслуживание"';
    protected $pageHeader = 'Метаданные Страница: "Медицинское обслуживание"';
    protected $pageId = 7;
    protected $backUrl = 'admin/medicalCarePage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
