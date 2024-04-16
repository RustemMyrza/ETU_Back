<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterSpecialtyPageDocument;
use App\Models\MastersSpecialty;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MasterSpecialtyPageDocumentController extends Controller
{
    public function index(Request $request, $mastersSpecialtyId)
    {
        $mastersSpecialtyName = MastersSpecialty::findOrFail($mastersSpecialtyId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $mastersSpecialtyDocument = MasterSpecialtyPageDocument::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $mastersSpecialtyDocument = MasterSpecialtyPageDocument::where('specialty_id', $mastersSpecialtyId)
                ->latest()
                ->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('mastersSpecialtyPageDocument.index', compact('mastersSpecialtyDocument', 'translatesData', 'mastersSpecialtyId', 'mastersSpecialtyName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($mastersSpecialtyId)
    {
        // dd($newsId);
        return view('mastersSpecialtyPageDocument.create', compact('mastersSpecialtyId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $mastersSpecialtyId)
    {
        // dd($request->all());
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

        $mastersSpecialtyDocument= new MasterSpecialtyPageDocument();
        $mastersSpecialtyDocument->name = $nameId;
        $mastersSpecialtyDocument->link = $path ?? null;
        $mastersSpecialtyDocument->specialty_id = $mastersSpecialtyId;
        $mastersSpecialtyDocument->save();

        return redirect('admin/mastersSpecialty/' . $mastersSpecialtyId . '/documents')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($mastersSpecialtyId, $id)
    {
        $mastersSpecialtyDocument = MasterSpecialtyPageDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($mastersSpecialtyDocument->name);
        return view('mastersSpecialtyPageDocument.show', compact('mastersSpecialtyDocument', 'translatedName', 'mastersSpecialtyId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($mastersSpecialtyId, $id)
    {
        $mastersSpecialtyDocument = MasterSpecialtyPageDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($mastersSpecialtyDocument->title);
        return view('mastersSpecialtyPageDocument.edit', compact('mastersSpecialtyDocument', 'translatedName', 'mastersSpecialtyId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id, $mastersSpecialtyId)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx|max:2048',
        ],
            [
                'document.mimes' => 'Формат документа должен быть pdf, docx или pptx',
                'document.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        $mastersSpecialtyDocument = MasterSpecialtyPageDocument::findOrFail($id);
        if ($request->hasFile('document')) {
            if ($mastersSpecialtyDocument->link != null) {
                Storage::disk('static')->delete($mastersSpecialtyDocument->link);
            }
            $path = $this->uploadDocument($request->file('document'));
            $mastersSpecialtyDocument->link = $path;
        }

        $name = Translate::find($mastersSpecialtyDocument->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $mastersSpecialtyDocument->update();

        return redirect('admin/mastersSpecialty/' . $mastersSpecialtyId . '/documents')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($mastersSpecialtyId, $id)
    {
        $mastersSpecialtyDocument = MasterSpecialtyPageDocument::find($id);
        if ($mastersSpecialtyDocument->link != null) {
            unlink($mastersSpecialtyDocument->image);
        }

        $name = Translate::find($mastersSpecialtyDocument->name);
        $name->delete();
        $mastersSpecialtyDocument->delete();

        return redirect('admin/mastersSpecialty/' . $mastersSpecialtyId . '/documents')->with('flash_message', 'Блок удален');
    }
}
