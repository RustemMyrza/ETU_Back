<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SchoolsPage;
use App\Models\Translate;


class SchoolsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

     private function addLink ($id)
     {
         switch($id){
             case 1:
                 $this->updateForLink('/1', $id);
                 break;
             case 2:
                 $this->updateForLink('/2', $id);
                 break;
             case 3:
                 $this->updateForLink('/3', $id);
                 break;
             case 4:
                 $this->updateForLink('/4', $id);
                 break;
         }
     }
 
     private function updateForLink ($link, $id)
     {
         $aboutPages = SchoolsPage::findOrFail($id);
         $aboutPages->link = $link;
         $aboutPages->update();
     }

    public function index (Request $request)
    {
        // dd("ok");
        $perPage = 25;
        $schoolsPages = SchoolsPage::latest()->paginate($perPage);
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('schoolsPages.index', compact('schoolsPages', 'translatesData'));
    }


    public function create ()
    {
        return view('schoolsPages.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */

    public function store (Request $request)
    {
        $requestData = $request->all();
        $translatesNavLink = new Translate();
        $translatesNavLink->ru = $requestData['content']['ru'];
        $translatesNavLink->kz = $requestData['content']['kz'];
        $translatesNavLink->en = $requestData['content']['en'];
        $translatesNavLink->save();
        $translateId = $translatesNavLink->id;

        $navLink = new SchoolsPage();
        $navLink->tab_name = $translateId;
        $navLink->parent_id = 4;
        $navLink->save();
        $this->addLink($navLink->id);

        return redirect('admin/schools')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */

    public function show ($id)
    {
        $schoolsPages = SchoolsPage::findOrFail($id);
        $translatedData = Translate::findOrFail($schoolsPages->tab_name)->ru;
        return view('schoolsPages.show', compact('schoolsPages', 'translatedData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */

    public function edit ($id)
    {
        $schoolsPages = SchoolsPage::findOrFail($id);
        return view('schoolsPages.edit', compact('schoolsPages'));
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
        $schoolsPages = SchoolsPage::findOrFail($id);

        $content = Translate::find($schoolsPages->tab_name);
        $content->ru = $requestData['content']['ru'];
        $content->kz = $requestData['content']['kz'];
        $content->en = $requestData['content']['en'];
        $content->update();

        $schoolsPages->update();

        return redirect('admin/schools')->with('flash_message', 'Блок изменен');
    }

    public function destroy($id)
    {
        // dd($id);
        $schoolsPages = SchoolsPage::find($id);
        $content = Translate::find($schoolsPages->tab_name);
        $content->delete();
        $schoolsPages->delete();

        return redirect('admin/schools')->with('flash_message', 'Блок удален');
    }
}
