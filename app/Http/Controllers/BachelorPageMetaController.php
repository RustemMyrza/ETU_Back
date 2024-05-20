<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class BachelorPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/bachelorPageMetaController';
    protected $pageTitle = 'Метаданные Страница: "Бакалавриат"';
    protected $pageHeader = 'Метаданные Страница: "Бакалавриат"';
    protected $pageId = 2;
    protected $backUrl = 'admin/bachelorSchool';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
