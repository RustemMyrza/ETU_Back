<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class AccreditationPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/accreditationPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Аккредитация"';
    protected $pageHeader = 'Метаданные Страница: "Аккредитация"';
    protected $pageId = 3;
    protected $backUrl = 'admin/accreditation';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
