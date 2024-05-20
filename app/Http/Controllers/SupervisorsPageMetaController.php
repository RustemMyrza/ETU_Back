<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class SupervisorsPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/supervisorsPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Руководство"';
    protected $pageHeader = 'Метаданные Страница: "Руководство"';
    protected $pageId = 2;
    protected $backUrl = 'admin/authority';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
