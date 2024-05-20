<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SciencePagesMeta;

class ScienceInnovationPageMetaController extends MetaDataController
{
    protected $modelClass = SciencePagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/scienceInnovationPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Наука и иновации"';
    protected $pageHeader = 'Метаданные Страница: "Наука и иновации"';
    protected $pageId = 1;
    protected $backUrl = 'admin/scienceInnovationPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}