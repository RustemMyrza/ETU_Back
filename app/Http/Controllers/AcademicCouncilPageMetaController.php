<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class AcademicCouncilPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/academicCouncilPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Ученый совет"';
    protected $pageHeader = 'Метаданные Страница: "Ученый совет"';
    protected $pageId = 8;
    protected $backUrl = 'admin/academicCouncilPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
