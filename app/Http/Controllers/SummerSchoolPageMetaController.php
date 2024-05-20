<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SciencePagesMeta;

class SummerSchoolPageMetaController extends MetaDataController
{
    protected $modelClass = SciencePagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/summerSchoolPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Летняя школа Daad"';
    protected $pageHeader = 'Метаданные Страница: "Летняя школа Daad"';
    protected $pageId = 4;
    protected $backUrl = 'admin/summerSchoolPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}