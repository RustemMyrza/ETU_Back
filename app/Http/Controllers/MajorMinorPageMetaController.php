<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class MajorMinorPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/majorMinorPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Major+ Minor"';
    protected $pageHeader = 'Метаданные Страница: "Major+ Minor"';
    protected $pageId = 6;
    protected $backUrl = 'admin/majorMinorPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
