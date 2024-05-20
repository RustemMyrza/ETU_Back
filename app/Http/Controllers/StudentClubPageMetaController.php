<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class StudentClubPageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/studentClubPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Студенческие клубы"';
    protected $pageHeader = 'Метаданные Страница: "Студенческие клубы"';
    protected $pageId = 10;
    protected $backUrl = 'admin/studentClubPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
