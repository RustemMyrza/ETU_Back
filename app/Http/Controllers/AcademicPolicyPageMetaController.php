<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentsPagesMeta;

class AcademicPolicyPageMetaController extends MetaDataController
{
    protected $modelClass = StudentsPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/academicPolicyPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Академическая политика"';
    protected $pageHeader = 'Метаданные Страница: "Академическая политика"';
    protected $pageId = 1;
    protected $backUrl = 'admin/academicPolicyPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
