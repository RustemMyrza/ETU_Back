<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class CareerCenterPageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/careerCenterPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Центр карьеры"';
    protected $pageHeader = 'Метаданные Страница: "Центр карьеры"';
    protected $pageId = 5;
    protected $backUrl = 'admin/careerCenterPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
