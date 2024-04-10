<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScientificPublicationPageDocument;

class ScientificPublicationPageDocumentController extends Controller
{
    protected $modelClass = ScientificPublicationPageDocument::class;
    protected $viewName = 'scientificPublicationPageDocument';
    protected $redirectUrl = 'admin/scientificPublicationPageDocument';

    public function index (Request $request)
    {
        $data = $this->getPage($request);
        $document = $data[0];
        $translatesData = $data[1];
        return view($this->viewName . '.index', compact('document', 'translatesData'));
    }
    public function create ()
    {
        return view('scientificPublicationPageDocument.create');
    }
    public function store (Request $request)
    {
        $this->saveData($request);
        return redirect($this->redirectUrl)->with('flash_message', 'Блок добавлен');
    }
    public function show ($id)
    {
        $data = $this->showPage($id);
        $document = $data[0];
        $translatedName = $data[1];
        return view($this->viewName . '.show', compact('document', 'translatedName'));
    }
    public function edit ($id)
    {
        $data = $this->editPage($id);
        $document = $data[0];
        $translatedName = $data[1];
        return view($this->viewName . '.edit', compact('document', 'translatedName'));
    }
    public function update (Request $request, $id)
    {
        $this->updateData($request, $id);
        return redirect($this->redirectUrl)->with('flash_message', 'Блок изменен');
    }
    public function destroy ($id)
    {
        $this->deleteData($id);
        return redirect($this->redirectUrl)->with('flash_message', 'Блок удален');
    }
}
