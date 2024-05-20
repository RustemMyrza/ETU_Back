<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SciencePagesMeta;

class ScientificPublicationPageMetaController extends MetaDataController
{
    protected $modelClass = SciencePagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/scientificPublicationPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Научные издания"';
    protected $pageHeader = 'Метаданные Страница: "Научные издания"';
    protected $pageId = 2;
    protected $backUrl = 'admin/scientificPublicationPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}