<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SciencePagesMeta;

class StudentSciencePageMetaController extends MetaDataController
{
    protected $modelClass = SciencePagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/studentSciencePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Студенческая наука"';
    protected $pageHeader = 'Метаданные Страница: "Студенческая наука"';
    protected $pageId = 3;
    protected $backUrl = 'admin/studentScience';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}