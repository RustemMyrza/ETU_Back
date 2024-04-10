<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

abstract class DocumentController extends Controller
{
    protected $modelClass;
    protected $viewName;
    protected $redirectUrl;

    abstract public function index(Request $request);
    abstract public function create();
    abstract public function store(Request $request);
    abstract public function show($id);
    abstract public function edit($id);
    abstract public function update(Request $request, $id);
    abstract public function destroy($id);

    protected function getPage (Request $request)
    {
        $modelClass = $this->modelClass;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $document = $modelClass::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $document = $modelClass::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        return [$document, $translatesData];

    }

    protected function saveData (Request $request)
    {
        $modelClass = $this->modelClass;
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx|max:2048',
        ], [
            'document.required' => 'Документ обязателен для загрузки',
            'document.mimes' => 'Формат документа должен быть pdf, docx или pptx',
            'document.max' => 'Размер файла не может превышать 2МБ'
        ]);
        $requestData = $request->all();
        if ($request->hasFile('document')) {
            $path = $this->uploadDocument($request->file('document'));
        }
        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;

        $document= new $modelClass();
        $document->name = $nameId;
        $document->link = $path ?? null;
        $document->save();
    }

    protected function showPage ($id)
    {
        $modelClass = $this->modelClass;
        $document = $modelClass::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        return [$document, $translatedName];
    }

    protected function editPage ($id)
    {
        $modelClass = $this->modelClass;
        $document = $modelClass::findOrFail($id);
        $translatedName = Translate::findOrFail($document->title);
        return [$document, $translatedName];
    }

    protected function updateData (Request $request, $id)
    {
        $modelClass = $this->modelClass;
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx|max:2048',
        ],
            [
                'document.mimes' => 'Формат документа должен быть pdf, docx или pptx',
                'document.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        $document = $modelClass::findOrFail($id);
        if ($request->hasFile('document')) {
            if ($document->document != null) {
                Storage::disk('static')->delete($document->document);
            }
            $path = $this->uploadImage($request->file('document'));
            $document->document = $path;
        }

        $name = Translate::find($document->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $document->update();
    }


    protected function deleteData ($id)
    {
        $modelClass = $this->modelClass;
        $document = $modelClass::find($id);
        if ($document->link != null) {
            Storage::disk('static')->delete($document->link);
        }
        $name = Translate::find($document->name);
        $name->delete();
        $document->delete();
    }
}
