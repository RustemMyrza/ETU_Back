<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class TravelGuidePageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/travelGuidePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Путеводитель для первокурсников"';
    protected $pageHeader = 'Метаданные Страница: "Путеводитель для первокурсников"';
    protected $pageId = 9;
    protected $backUrl = 'admin/travelGuidePage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
