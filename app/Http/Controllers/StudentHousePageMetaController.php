<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class StudentHousePageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/studentHousePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Дом студентов"';
    protected $pageHeader = 'Метаданные Страница: "Дом студентов"';
    protected $pageId = 8;
    protected $backUrl = 'admin/studentHousePage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
