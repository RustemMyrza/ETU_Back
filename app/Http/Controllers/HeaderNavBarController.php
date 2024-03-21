<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\HeaderNavBar;
use App\Models\Translate;

class HeaderNavBarController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $perPage = 25;
        $headerNavBar = HeaderNavBar::latest()->paginate($perPage);
        return view('headerNavBar.index', compact('headerNavBar'));
    }

    public function create()
    {
        return view('headerNavBar.create');
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
        $translatesNavLink = new Translate();
        $translatesNavLink->ru = $requestData['content']['ru'];
        $translatesNavLink->kz = $requestData['content']['kz'];
        $translatesNavLink->en = $requestData['content']['en'];
        $translatesNavLink->save();
        $translateId = $translatesNavLink->id;

        $navLink = new HeaderNavBar();
        $navLink->tab_name = $translateId;
        $navLink->save();

        return redirect('admin/navbar')->with('flash_message', 'Блок добавлен');
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
        $headerNavBar = HeaderNavBar::findOrFail($id);
        $translatedData = Translate::findOrFail($headerNavBar->tab_name)->ru;
        return view('headerNavBar.show', compact('headerNavBar', 'translatedData'));
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
        $headerNavBar = HeaderNavBar::findOrFail($id);
        return view('headerNavBar.edit', compact('headerNavBar'));
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
        $headerNavBar = HeaderNavBar::findOrFail($id);

        $content = Translate::find($headerNavBar->tab_name);
        $content->ru = $requestData['content']['ru'];
        $content->kz = $requestData['content']['kz'];
        $content->en = $requestData['content']['en'];
        $content->update();

        $headerNavBar->update();

        return redirect('admin/navbar')->with('flash_message', 'Блок изменен');
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
        // dd($id);
        $headerNavBar = HeaderNavBar::find($id);
        $content = Translate::find($headerNavBar->tab_name);
        $content->delete();
        $headerNavBar->delete();

        return redirect('admin/navbar')->with('flash_message', 'Блок удален');
    }
}
