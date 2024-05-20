<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsMeta;

class NewsMetaController extends MetaDataByParentController
{
    protected $modelClass = NewsMeta::class;
    protected $parentModelClass = News::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/news/%d/meta';
    protected $backUrl = 'admin/news/';

    public function index($parentId)
    {
        return $this->getPage($parentId);
    }

    public function store(Request $request, $parentId)
    {
        return $this->saveData($request, $parentId);
    }
}
