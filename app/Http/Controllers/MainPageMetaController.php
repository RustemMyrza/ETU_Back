<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainPageMeta;

class MainPageMetaController extends MetaDataController
{
    protected $modelClass = MainPageMeta::class;
    protected $viewName = 'metaData.index';
    protected $redirectUrl = 'admin/mainPageMeta';
    protected $pageTitle = 'Метаданные (Главная страница)';
    protected $pageHeader = 'Метаданные (Главная страница)';
    protected $backUrl = 'admin/mainPage';

    public function index()
    {
        $modelClass = $this->modelClass;
        $viewName = $this->viewName;
        $pageTitle = $this->pageTitle;
        $pageHeader = $this->pageHeader;
        $redirectUrl = $this->redirectUrl;
        $backUrl = url($this->backUrl);
        $formAction = url($redirectUrl);
        $metaData = $modelClass::first();
        return view($viewName, compact([
            'metaData',
            'pageTitle',
            'pageHeader',
            'formAction',
            'backUrl'
        ]));
    }

    public function store(Request $request)
    {
        $modelClass = $this->modelClass;
        $redirectUrl = $this->redirectUrl;
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
        $metaData = $modelClass::first();
        if ($metaData) {
            $metaData->name = $requestData['name'];
            $metaData->description = $requestData['description'];
            $metaData->keyword = implode(', ', $keywords);
            $metaData->update();
        } else {
            $modelClass::create([
                'name' => $requestData['name'],
                'description' => $requestData['description'],
                'keyword' => implode(', ', $keywords)
            ]);
        }

        return redirect($redirectUrl)->with('success', 'Изменения сохранены');
    }

}
