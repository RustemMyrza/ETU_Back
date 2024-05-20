<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class AboutUsPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/aboutUsPageMeta';
    protected $pageTitle = 'Метаданные Страница: "О нас"';
    protected $pageHeader = 'Метаданные Страница: "О нас"';
    protected $pageId = 1;
    protected $backUrl = 'admin/aboutUs';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
