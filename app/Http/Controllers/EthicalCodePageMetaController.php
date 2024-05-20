<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class EthicalCodePageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/ethicalCodePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Этический кодекс"';
    protected $pageHeader = 'Метаданные Страница: "Этический кодекс"';
    protected $pageId = 4;
    protected $backUrl = 'admin/ethicsCodePage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
