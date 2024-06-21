<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AboutUniversityPage;
use App\Models\Translate;


class AboutUniversityPageController extends Controller
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
                $this->updateForLink('/about', $id);
                break;
            case 2:
                $this->updateForLink('/about-management', $id);
                break;
            case 3:
                $this->updateForLink('/about-accreditation', $id);
                break;
            case 4:
                $this->updateForLink('/about-partners', $id);
                break;
            case 5:
                $this->updateForLink('/about-rector-blogs', $id);
                break;
            case 6:
                $this->updateForLink('/about-career', $id);
                break;
            case 7:
                $this->updateForLink('/about-science', $id);
                break;
            case 8:
                $this->updateForLink('/about-academic-council', $id);
                break;
            case 9:
                $this->updateForLink('/about-infrastructure', $id);
                break;
        }
    }

    private function updateForLink ($link, $id)
    {
        $aboutPages = AboutUniversityPage::findOrFail($id);
        $aboutPages->link = $link;
        $aboutPages->update();
    } 

    public function index (Request $request)
    {
        // dd("ok");
        $perPage = 25;
        $aboutPages = AboutUniversityPage::latest()->paginate($perPage);
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('aboutUniversityPages.index', compact('aboutPages', 'translatesData'));
    }


    public function create ()
    {
        return view('aboutUniversityPages.create');
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

        $navLink = new AboutUniversityPage();
        $navLink->tab_name = $translateId;
        $navLink->parent_id = 1;
        $navLink->save();
        $this->addLink($navLink->id);

        return redirect('admin/university')->with('flash_message', 'Блок добавлен');
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
        $aboutPages = AboutUniversityPage::findOrFail($id);
        $translatedData = Translate::findOrFail($aboutPages->tab_name)->ru;
        return view('aboutUniversityPages.show', compact('aboutPages', 'translatedData'));
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
        $aboutPages = AboutUniversityPage::findOrFail($id);
        return view('aboutUniversityPages.edit', compact('aboutPages'));
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
        $aboutPages = AboutUniversityPage::findOrFail($id);

        $content = Translate::find($aboutPages->tab_name);
        $content->ru = $requestData['content']['ru'];
        $content->kz = $requestData['content']['kz'];
        $content->en = $requestData['content']['en'];
        $content->update();

        $aboutPages->update();

        return redirect('admin/university')->with('flash_message', 'Блок изменен');
    }

    public function destroy($id)
    {
        // dd($id);
        $aboutPages = AboutUniversityPage::find($id);
        $content = Translate::find($aboutPages->tab_name);
        $content->delete();
        $aboutPages->delete();

        return redirect('admin/university')->with('flash_message', 'Блок удален');
    }


}
