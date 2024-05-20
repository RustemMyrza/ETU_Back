<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class MetaDataByParentController extends Controller
{
    protected $modelClass;
    protected $parentModelClass;
    protected $redirectUrl;
    protected $backUrl;
    protected $viewName;

    abstract public function index($parent_id);
    abstract public function store(Request $request, $parent_id);

    protected function getPage($parent_id)
    {
        $modelClass = $this->modelClass;
        $viewName = $this->viewName;
        $redirectUrl = $this->redirectUrl;
        $parentModelClass = $this->parentModelClass;
        $backUrl = $this->backUrl;
        $pageId = $parent_id;
        $parentData = $parentModelClass::findOrFail($parent_id);
        $parentName = $parentData->getName->ru;
        $pageTitle = 'Метаданные страница: "' . $parentName . '"';
        $pageHeader = 'Метаданные страница: "' . $parentName . '"';
        $backUrl = url(sprintf($backUrl, $parent_id));
        $formAction = url(sprintf($redirectUrl, $parent_id));
        $metaData = $modelClass::where('page_id', $parent_id)->first();
        return view($viewName, compact([
            'metaData',
            'pageTitle',
            'pageHeader',
            'formAction',
            'backUrl',
            'parentData'
        ]));
    }

    protected function saveData(Request $request, $parent_id)
    {
        $modelClass = $this->modelClass;
        $redirectUrl = $this->redirectUrl;
        $redirectUrl = url(sprintf($redirectUrl, $parent_id));
        $pageId = $parent_id;
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
