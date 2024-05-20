<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class MetaDataController extends Controller
{
    protected $modelClass;
    protected $redirectUrl;
    protected $viewName;
    protected $pageTitle;
    protected $pageHeader;
    protected $pageId;
    protected $backUrl;

    abstract public function index();
    abstract public function store(Request $request);

    protected function getPage()
    {
        $modelClass = $this->modelClass;
        $viewName = $this->viewName;
        $pageTitle = $this->pageTitle;
        $pageHeader = $this->pageHeader;
        $redirectUrl = $this->redirectUrl;
        $pageId = $this->pageId;
        $backUrl = url($this->backUrl);
        $formAction = url($redirectUrl);
        $metaData = $modelClass::where('page_id', $pageId)->first();
        return view($viewName, compact([
            'metaData',
            'pageTitle',
            'pageHeader',
            'formAction',
            'backUrl'
        ]));
    }

    protected function saveData(Request $request)
    {
        $modelClass = $this->modelClass;
        $redirectUrl = $this->redirectUrl;
        $pageId = $this->pageId;
        $requestData = $request->all();
        foreach($requestData as $key => $value)
        {
            if ($key != '_token' && $key != 'name' && $key != 'description')
            {
                if ($value != '')
                {
                    $keywords[] = $value; 
                }
            }
        }
        $metaData = $modelClass::where('page_id', $pageId)->first();
        if ($metaData) {
            $metaData->name = $requestData['name'];
            $metaData->description = $requestData['description'];
            $metaData->keyword = implode(', ', $keywords);
            $metaData->page_id = $pageId;
            $metaData->update();
        } else {
            $newMetaData = new $modelClass;
            $newMetaData->name = $requestData['name'];
            $newMetaData->description = $requestData['description'];
            $newMetaData->keyword = implode(', ', $keywords);
            $newMetaData->page_id = $pageId;
            $newMetaData->save();
        }

        return redirect($redirectUrl)->with('success', 'Изменения сохранены');

    }
}
