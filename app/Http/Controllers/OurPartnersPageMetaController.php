<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUniversityPagesMeta;

class OurPartnersPageMetaController extends MetaDataController
{
    protected $modelClass = AboutUniversityPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/ourPartnersPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Наши партнеры"';
    protected $pageHeader = 'Метаданные Страница: "Наши партнеры"';
    protected $pageId = 4;
    protected $backUrl = 'admin/partnersPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
