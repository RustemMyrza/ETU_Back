<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnrollmentPage;
use App\Models\Translate;


class EnrollmentPageController extends Controller
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
                 $this->updateForLink('/admission', $id);
                 break;
             case 2:
                 $this->updateForLink('/admission-bachelors-degree', $id);
                 break;
             case 3:
                 $this->updateForLink('/masters', $id);
                 break;
             case 4:
                 $this->updateForLink('/admission-for-foreign-students', $id);
                 break;
             case 5:
                 $this->updateForLink('/admission-language-classes', $id);
                 break;
             case 6:
                 $this->updateForLink('/admission-major-minor', $id);
                 break;
             case 7:
                 $this->updateForLink('/admission-level-up', $id);
                 break;
             case 8:
                 $this->updateForLink('/admission-olympics', $id);
                 break;
            case 9:
                $this->updateForLink('/admission-lincoln-university', $id);
                break;
         }
     }
 
     private function updateForLink ($link, $id)
     {
         $aboutPages = EnrollmentPage::findOrFail($id);
         $aboutPages->link = $link;
         $aboutPages->update();
     }

    public function index (Request $request)
    {
        // dd("ok");
        $perPage = 25;
        $enrollmentPages = EnrollmentPage::latest()->paginate($perPage);
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('enrollmentPages.index', compact('enrollmentPages', 'translatesData'));
    }


    public function create ()
    {
        return view('enrollmentPages.create');
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

        $navLink = new EnrollmentPage();
        $navLink->tab_name = $translateId;
        $navLink->parent_id = 2;
        $navLink->save();
        $this->addLink($navLink->id);

        return redirect('admin/enrollment')->with('flash_message', 'Блок добавлен');
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
        $enrollmentPages = EnrollmentPage::findOrFail($id);
        $translatedData = Translate::findOrFail($enrollmentPages->tab_name)->ru;
        return view('enrollmentPages.show', compact('enrollmentPages', 'translatedData'));
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
        $enrollmentPages = EnrollmentPage::findOrFail($id);
        return view('enrollmentPages.edit', compact('enrollmentPages'));
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
        $enrollmentPages = EnrollmentPage::findOrFail($id);

        $content = Translate::find($enrollmentPages->tab_name);
        $content->ru = $requestData['content']['ru'];
        $content->kz = $requestData['content']['kz'];
        $content->en = $requestData['content']['en'];
        $content->update();

        $enrollmentPages->update();

        return redirect('admin/enrollment')->with('flash_message', 'Блок изменен');
    }

    public function destroy($id)
    {
        // dd($id);
        $enrollmentPages = EnrollmentPage::find($id);
        $content = Translate::find($enrollmentPages->tab_name);
        $content->delete();
        $enrollmentPages->delete();

        return redirect('admin/enrollment')->with('flash_message', 'Блок удален');
    }
}
