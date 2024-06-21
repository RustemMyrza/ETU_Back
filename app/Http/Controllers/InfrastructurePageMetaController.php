<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class InfrastructurePageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/infrastructurePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Инфраструктура"';
    protected $pageHeader = 'Метаданные Страница: "Инфраструктура"';
    protected $pageId = 9;
    protected $backUrl = 'admin/infrastructure';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
