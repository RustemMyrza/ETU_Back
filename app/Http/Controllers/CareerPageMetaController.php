<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class CareerPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/careerPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Карьера и вакансии"';
    protected $pageHeader = 'Метаданные Страница: "Карьера и вакансии"';
    protected $pageId = 6;
    protected $backUrl = 'admin/careerPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
