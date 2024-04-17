<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SciencePage;
use App\Models\Translate;


class SciencePageController extends Controller
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
                 $this->updateForLink('/science', $id);
                 break;
             case 2:
                 $this->updateForLink('/science-publications', $id);
                 break;
             case 3:
                 $this->updateForLink('/science-student-science', $id);
                 break;
            case 4:
                $this->updateForLink('/summer-school', $id);
                break;
         }
     }
 
     private function updateForLink ($link, $id)
     {
         $aboutPages = SciencePage::findOrFail($id);
         $aboutPages->link = $link;
         $aboutPages->update();
     }

    public function index (Request $request)
    {
        // dd("ok");
        $perPage = 25;
        $sciencePages = SciencePage::latest()->paginate($perPage);
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('sciencePages.index', compact('sciencePages', 'translatesData'));
    }


    public function create ()
    {
        return view('sciencePages.create');
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

        $navLink = new SciencePage();
        $navLink->tab_name = $translateId;
        $navLink->parent_id = 5;
        $navLink->save();
        $this->addLink($navLink->id);

        return redirect('admin/science')->with('flash_message', 'Блок добавлен');
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
        $sciencePages = SciencePage::findOrFail($id);
        $translatedData = Translate::findOrFail($sciencePages->tab_name)->ru;
        return view('sciencePages.show', compact('sciencePages', 'translatedData'));
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
        $sciencePages = SciencePage::findOrFail($id);
        return view('sciencePages.edit', compact('sciencePages'));
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
        $sciencePages = SciencePage::findOrFail($id);

        $content = Translate::find($sciencePages->tab_name);
        $content->ru = $requestData['content']['ru'];
        $content->kz = $requestData['content']['kz'];
        $content->en = $requestData['content']['en'];
        $content->update();

        $sciencePages->update();

        return redirect('admin/science')->with('flash_message', 'Блок изменен');
    }

    public function destroy($id)
    {
        // dd($id);
        $sciencePages = SciencePage::find($id);
        $content = Translate::find($sciencePages->tab_name);
        $content->delete();
        $sciencePages->delete();

        return redirect('admin/science')->with('flash_message', 'Блок удален');
    }
}
