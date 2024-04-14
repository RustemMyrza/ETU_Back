<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSpecialtyDocument;
use App\Models\BachelorSchoolSpecialty;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class BachelorSpecialtyDocumentController extends Controller
{
    public function index(Request $request, $schoolId, $specialtyId)
    {
        $specialtyName = BachelorSchoolSpecialty::findOrFail($specialtyId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $document = BachelorSpecialtyDocument::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $document = BachelorSpecialtyDocument::where('specialty_id', $specialtyId)
                ->latest()
                ->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('bachelorSpecialtyDocument.index', compact('document', 'translatesData', 'schoolId', 'specialtyId', 'specialtyName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($schoolId, $specialtyId)
    {
        // dd($newsId);
        return view('bachelorSpecialtyDocument.create', compact('schoolId', 'specialtyId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $schoolId, $specialtyId)
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

        $document= new BachelorSpecialtyDocument();
        $document->name = $nameId;
        $document->link = $path ?? null;
        $document->specialty_id = $specialtyId;
        $document->save();

        return redirect(route('bachelorSpecialty.document.index', ['schoolId' => $schoolId, 'specialtyId' => $specialtyId]))->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($schoolId, $specialtyId, $id)
    {
        $document = BachelorSpecialtyDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        return view('bachelorSpecialtyDocument.show', compact('document', 'translatedName', 'schoolId', 'specialtyId', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($schoolId, $specialtyId, $id)
    {
        $document = BachelorSpecialtyDocument::findOrFail($id);
        $translatedName = Translate::findOrFail($document->name);
        return view('bachelorSpecialtyDocument.edit', compact('document', 'translatedName', 'schoolId', 'specialtyId', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $schoolId, $specialtyId, $id)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,pptx|max:2048',
        ],
            [
                'document.mimes' => 'Формат документа должен быть pdf, docx или pptx',
                'document.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        $document = BachelorSpecialtyDocument::findOrFail($id);
        if ($request->hasFile('document')) {
            if ($document->link != null) {
                Storage::disk('static')->delete($document->link);
            }
            $path = $this->uploadDocument($request->file('document'));
            $document->link = $path;
        }

        $name = Translate::find($document->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $document->update();

        return redirect(route('bachelorSpecialty.document.index', ['schoolId' => $schoolId, 'specialtyId' => $specialtyId]))->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($schoolId, $specialtyId, $id)
    {
        $document = BachelorSpecialtyDocument::find($id);
        if ($document->link != null) {
            Storage::disk('static')->delete($document->link);
        }

        $name = Translate::find($document->name);
        $name->delete();
        $document->delete();

        return redirect(route('bachelorSpecialty.document.index', ['schoolId' => $schoolId, 'specialtyId' => $specialtyId]))->with('flash_message', 'Блок удален');
    }
}
