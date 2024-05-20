<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class LibraryPageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/libraryPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Библиотека"';
    protected $pageHeader = 'Метаданные Страница: "Библиотека"';
    protected $pageId = 3;
    protected $backUrl = 'admin/libraryPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
