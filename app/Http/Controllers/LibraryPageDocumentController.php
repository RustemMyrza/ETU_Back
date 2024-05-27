<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibraryPageDocument;

class LibraryPageDocumentController extends DocumentController
{
    protected $modelClass = LibraryPageDocument::class;
    protected $viewName = 'libraryPageDocument';
    protected $redirectUrl = 'admin/libraryPageDocument';

    public function index (Request $request)
    {
        $data = $this->getPage($request);
        $document = $data[0];
        $translatesData = $data[1];
        return view($this->viewName . '.index', compact('document', 'translatesData'));
    }
    public function create ()
    {
        if (count(LibraryPageDocument::all()) == 1)
        {
            return redirect($this->redirectUrl)->with('error', 'Вы не можете добавить больше одного элемента');
        }
        return view('libraryPageDocument.create');
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
