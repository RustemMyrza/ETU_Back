<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class AcademicCalendarPageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/academicCalendarPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Академический календарь"';
    protected $pageHeader = 'Метаданные Страница: "Академический календарь"';
    protected $pageId = 2;
    protected $backUrl = 'admin/academicCalendarPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
