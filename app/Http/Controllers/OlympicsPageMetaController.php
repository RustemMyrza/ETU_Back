<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class OlympicsPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/olympicsPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Олимпиада"';
    protected $pageHeader = 'Метаданные Страница: "Олимпиада"';
    protected $pageId = 8;
    protected $backUrl = 'admin/olympicsPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
