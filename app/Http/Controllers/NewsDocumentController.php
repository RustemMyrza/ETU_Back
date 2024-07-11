<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsDocument;
use App\Models\News;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class NewsDocumentController extends Controller
{
    public function index(Request $request, $newsId)
    {
        $newsName = News::findOrFail($newsId)->with('getName')->get()[0]->getName->ru;
        $perPage = 25;

        $newsDocuments = NewsDocument::where('parent_id', $newsId)
            ->latest()
            ->paginate($perPage);
        
        return view('newsDocument.index', compact('newsDocuments', 'newsId', 'newsName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($newsId)
    {
        // dd($newsId);
        return view('newsDocument.create', compact('newsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $newsId)
    {
        // dd($request->all());
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx,xlsx|max:10240',
        ],
            [
                'document.required' => 'Документ для блока обязательно',
                'document.mimes' => 'Проверьте формат документа',
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

        $newsDocuments= new NewsDocument();
        $newsDocuments->name = $nameId;
        $newsDocuments->document_link = $path ?? null;
        $newsDocuments->parent_id = $newsId;
        $newsDocuments->save();

        return redirect('admin/news/' . $newsId . '/document')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($newsId, $id)
    {
        $newsDocuments = NewsDocument::findOrFail($id);
        return view('newsDocument.show', compact('newsDocuments', 'newsId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($newsId, $id)
    {
        $newsDocuments = NewsDocument::findOrFail($id);
        return view('newsDocument.edit', compact('newsDocuments', 'newsId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $newsId, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx,xlsx|max:10240',
        ],
            [
                'document.mimes' => 'Проверьте формат документа',
                'document.max' => 'Размер файла не может превышать 10МБ'
            ]);

        $requestData = $request->all();
        $newsDocuments = NewsDocument::findOrFail($id);
        if ($request->hasFile('document')) {
            if ($newsDocuments->document_link != null) {
                unlink($newsDocuments->document_link);
            }
            $path = $this->uploadDocument($request->file('document'));
            $newsDocuments->document_link = $path;
        }

        $name = Translate::find($newsDocuments->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();
        $newsDocuments->update();

        return redirect('admin/news/' . $newsId . '/document')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($newsId, $id)
    {
        $newsDocuments = NewsDocument::find($id);
        if ($newsDocuments->document_link != null) {
            unlink($newsDocuments->document_link);
        }

        $name = Translate::find($newsDocuments->name);
        $name->delete();
        $newsDocuments->delete();

        return redirect('admin/news/' . $newsId . '/document')->with('flash_message', 'Блок удален');
    }
}
