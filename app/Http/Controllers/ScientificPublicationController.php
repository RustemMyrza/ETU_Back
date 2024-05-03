<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScientificPublicationPageDocument;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class ScientificPublicationController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $document = ScientificPublicationPageDocument::where('name', 'LIKE', "%$keyword%")
                ->orWhere('author', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $document = ScientificPublicationPageDocument::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('scientificPublicationDocument.index', compact('document', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // dd($newsId);
        return view('scientificPublicationDocument.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240',
        ], [
            'document.required' => 'Документ обязателен для загрузки',
            'document.mimes' => 'Формат документа должен быть pdf',
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

        if ($requestData['author']['ru'])
        {
            $author = new Translate();
            $author->ru = $requestData['author']['ru'];
            $author->en = $requestData['author']['en'];
            $author->kz = $requestData['author']['kz'];
            $author->save();
            $authorId = $author->id;
        }

        $document= new ScientificPublicationPageDocument();
        $document->name = $nameId;
        $document->author = $authorId;
        $document->link = $path ?? null;
        $document->save();

        return redirect('admin/scientificPublicationDocument')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $document = ScientificPublicationPageDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        $translatedAuthor = Translate::findOrFail($document->author);
        $translatedData['name'] = $translatedName;
        $translatedData['author'] = $translatedAuthor;
        return view('scientificPublicationDocument.show', compact('document', 'translatedData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $document = ScientificPublicationPageDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        $translatedName = Translate::findOrFail($document->name);
        $translatedAuthor = Translate::findOrFail($document->author);
        $translatedData['name'] = $translatedName;
        $translatedData['author'] = $translatedAuthor;
        return view('scientificPublicationDocument.edit', compact('document', 'translatedData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf|max:10240',
        ],
            [
                'document.mimes' => 'Формат документа должен быть pdf',
                'document.max' => 'Размер файла не может превышать 10МБ'
            ]);

        $requestData = $request->all();
        $document = ScientificPublicationPageDocument::findOrFail($id);
        if ($request->hasFile('document')) {
            if ($document->link != null) {
                unlink($document->link);
            }
            $path = $this->uploadDocument($request->file('document'));
            $document->link = $path;
        }

        $name = Translate::find($document->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        if ($requestData['author']['ru'])
        {
            if ($document->author)
            {
                $author = new Translate();
                $author->ru = $requestData['author']['ru'];
                $author->en = $requestData['author']['en'];
                $author->kz = $requestData['author']['kz'];
                $author->save();
            }
            else
            {
                $author = Translate::findOrFail($document->author);
                $author->ru = $requestData['author']['ru'];
                $author->en = $requestData['author']['en'];
                $author->kz = $requestData['author']['kz'];
                $author->update();
            }
        }
        else
        {
            if ($document->author)
            {
                $author = Translate::findOrFail($document->author);
                $document->author = null;
                $author->delete();
            }
        }

        $document->update();

        return redirect('admin/scientificPublicationDocument')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $document = ScientificPublicationPageDocument::find($id);
        if ($document->link != null) {
            unlink($document->link);
        }

        $name = Translate::find($document->name);
        $author = Translate::findOrFail($document->author);
        $author->delete();
        $name->delete();
        $document->delete();

        return redirect('admin/scientificPublicationDocument')->with('flash_message', 'Блок удален');
    }
}
