<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class InternationalStudentsPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/internationalStudentsPageMetaController';
    protected $pageTitle = 'Метаданные Страница: "Иностранные студенты"';
    protected $pageHeader = 'Метаданные Страница: "Иностранные студенты"';
    protected $pageId = 4;
    protected $backUrl = 'admin/internationalStudentsPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
