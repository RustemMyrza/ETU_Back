<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class RectorsBlogPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/rectorsBlogPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Блог ректора"';
    protected $pageHeader = 'Метаданные Страница: "Блог ректора"';
    protected $pageId = 5;
    protected $backUrl = 'admin/rectorsBlogPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
