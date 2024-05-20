<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class LanguageCoursesPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/languageCoursesPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Языковые курсы"';
    protected $pageHeader = 'Метаданные Страница: "Языковые курсы"';
    protected $pageId = 5;
    protected $backUrl = 'admin/languageCoursesPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
