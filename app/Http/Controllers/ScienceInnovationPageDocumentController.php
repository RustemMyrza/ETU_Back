<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScienceInnovationPageDocument;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class ScienceInnovationPageDocumentController extends Controller
{
    public function index (Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $document = ScienceInnovationPageDocument::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $document = ScienceInnovationPageDocument::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        return view('scienceInnovationPageDocument.index', compact('document', 'translatesData'));
    }


    public function create ()
    {
        return view('scienceInnovationPageDocument.create');
    }


    public function store (Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx|max:10240',
        ], [
            'document.required' => 'Документ обязателен для загрузки',
            'document.mimes' => 'Формат документа должен быть pdf, docx или pptx',
            'document.max' => 'Размер файла не может превышать 10МБ'
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

        $document= new ScienceInnovationPageDocument();
        $document->name = $nameId;
        $document->link = $path ?? null;
        $document->block_id = $requestData['block_id'];
        $document->save();
        return redirect('admin/scienceInnovationPageDocument')->with('flash_message', 'Блок добавлен');
    }


    public function show ($id)
    {
        $document = ScienceInnovationPageDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        return view('scienceInnovationPageDocument.show', compact('document', 'translatedName'));
    }


    public function edit ($id)
    {
        $document = ScienceInnovationPageDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        return view('scienceInnovationPageDocument.edit', compact('document', 'translatedName'));
    }


    public function update (Request $request, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx|max:10240',
        ],
            [
                'document.mimes' => 'Формат документа должен быть pdf, docx или pptx',
                'document.max' => 'Размер файла не может превышать 10МБ'
            ]);

        $requestData = $request->all();
        $document = ScienceInnovationPageDocument::findOrFail($id);
        if ($request->hasFile('document')) {
            if ($document->link != null) {
                unlink($document->link);
            }
            $path = $this->uploadDocument($request->file('document'));
            $document->link = $path;
        }

        $document->block_id = $requestData['block_id'];

        $name = Translate::find($document->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        

        $document->update();
        return redirect('admin/scienceInnovationPageDocument')->with('flash_message', 'Блок изменен');
    }


    public function destroy ($id)
    {
        $document = ScienceInnovationPageDocument::find($id);
        if ($document->link != null) {
            unlink($document->link);
        }
        $name = Translate::find($document->name);
        $name->delete();
        $document->delete();
        return redirect('admin/scienceInnovationPageDocument')->with('flash_message', 'Блок удален');
    }
}
