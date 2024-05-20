<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class MilitaryDepartmentPageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/militaryDepartmentPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Военная кафедра"';
    protected $pageHeader = 'Метаданные Страница: "Военная кафедра"';
    protected $pageId = 6;
    protected $backUrl = 'admin/militaryDepartmentPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
