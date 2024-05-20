<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class LevelUpPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/levelUpPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Level up"';
    protected $pageHeader = 'Метаданные Страница: "Level up"';
    protected $pageId = 7;
    protected $backUrl = 'admin/levelUpPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
