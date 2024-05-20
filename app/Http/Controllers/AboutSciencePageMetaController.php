<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class AboutSciencePageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/aboutSciencePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Наука"';
    protected $pageHeader = 'Метаданные Страница: "Наука"';
    protected $pageId = 7;
    protected $backUrl = 'admin/scienceAboutPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
