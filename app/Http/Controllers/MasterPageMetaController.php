<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentPagesMeta;

class MasterPageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/masterPageMeta';
    protected $pageTitle = 'Метаданные Страница: "Магистратура"';
    protected $pageHeader = 'Метаданные Страница: "Магистратура"';
    protected $pageId = 3;
    protected $backUrl = 'admin/masterPage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
