<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentPagesMeta;
use Illuminate\Http\Request;

class AdmissionsCommitteePageMetaController extends MetaDataController
{
    protected $modelClass = EnrollmentPagesMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/admissionsCommitteePageMeta';
    protected $pageTitle = 'Метаданные Страница: "Приемная комиссия"';
    protected $pageHeader = 'Метаданные Страница: "Приемная комиссия"';
    protected $pageId = 1;
    protected $backUrl = 'admin/admissionsCommitteePage';

    public function index()
    {
        return $this->getPage();
    }

    public function store(Request $request)
    {
        return $this->saveData($request);
    }
}
