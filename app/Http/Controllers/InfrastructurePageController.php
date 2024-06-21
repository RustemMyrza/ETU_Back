<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfrastructurePage;
use App\Models\Translate;

class InfrastructurePageController extends Controller
{
    public function index()
    {
        $perPage = 25;
        $infrastructurePage = InfrastructurePage::latest()->paginate($perPage);
        return view('infrastructurePage.index', compact('infrastructurePage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('infrastructurePage.create');
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
        $requestData = $request->all();

        $title = new Translate();
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->save();
        $titleId = $title->id;


        $infrastructurePage= new InfrastructurePage();
        $infrastructurePage->title = $titleId;
        $infrastructurePage->save();

        return redirect('admin/infrastructure')->with('flash_message', 'Блок добавлен');
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
        $infrastructurePage = InfrastructurePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($infrastructurePage->title);
        return view('infrastructurePage.show', compact('infrastructurePage'));
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
        $infrastructurePage = InfrastructurePage::findOrFail($id);
        return view('infrastructurePage.edit', compact('infrastructurePage'));
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
        $requestData = $request->all();

        $infrastructurePage = InfrastructurePage::findOrFail($id);

        $title = Translate::find($infrastructurePage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $infrastructurePage->update();

        return redirect('admin/infrastructure')->with('flash_message', 'Блок изменен');
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
        $infrastructurePage = InfrastructurePage::find($id);
        $title = Translate::find($infrastructurePage->title);
        $title->delete();
        $infrastructurePage->delete();

        return redirect('admin/infrastructure')->with('flash_message', 'Блок удален');
    }
}
