<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class LincolnUniversityPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/lincolnUniversityPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Программы двойного диплома Lincoln University"';
    protected $pageHeader = 'Метаданные Страница: "Программы двойного диплома Lincoln University"';
    protected $pageId = 9;
    protected $backUrl = 'admin/lincolnUniversityPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
