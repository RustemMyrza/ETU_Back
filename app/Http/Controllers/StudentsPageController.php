<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StudentsPage;
use App\Models\Translate;


class StudentsPageController extends Controller
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
                 $this->updateForLink('/student-academic-policy', $id);
                 break;
             case 2:
                 $this->updateForLink('/student-academic-calendar', $id);
                 break;
             case 3:
                 $this->updateForLink('/student-library', $id);
                 break;
             case 4:
                 $this->updateForLink('/students-code-of-ethics', $id);
                 break;
             case 5:
                 $this->updateForLink('/student-career-center', $id);
                 break;
             case 6:
                 $this->updateForLink('/student-military-department', $id);
                 break;
             case 7:
                 $this->updateForLink('/student-medical-service', $id);
                 break;
             case 8:
                 $this->updateForLink('/student-house', $id);
                 break;
            case 9:
                $this->updateForLink('/students-guide-for-freshmen', $id);
                break;
            case 10:
                $this->updateForLink('/students-clubs', $id);
                break;
         }
     }
 
     private function updateForLink ($link, $id)
     {
         $aboutPages = StudentsPage::findOrFail($id);
         $aboutPages->link = $link;
         $aboutPages->update();
     }

    public function index (Request $request)
    {
        // dd("ok");
        $perPage = 25;
        $studentsPages = StudentsPage::latest()->paginate($perPage);
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('studentsPages.index', compact('studentsPages', 'translatesData'));
    }


    public function create ()
    {
        return view('studentsPages.create');
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

        $navLink = new StudentsPage();
        $navLink->tab_name = $translateId;
        $navLink->parent_id = 3;
        $navLink->save();
        $this->addLink($navLink->id);

        return redirect('admin/students')->with('flash_message', 'Блок добавлен');
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
        $studentsPages = StudentsPage::findOrFail($id);
        $translatedData = Translate::findOrFail($studentsPages->tab_name)->ru;
        return view('studentsPages.show', compact('studentsPages', 'translatedData'));
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
        $studentsPages = StudentsPage::findOrFail($id);
        return view('studentsPages.edit', compact('studentsPages'));
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
        $studentsPages = StudentsPage::findOrFail($id);

        $content = Translate::find($studentsPages->tab_name);
        $content->ru = $requestData['content']['ru'];
        $content->kz = $requestData['content']['kz'];
        $content->en = $requestData['content']['en'];
        $content->update();

        $studentsPages->update();

        return redirect('admin/students')->with('flash_message', 'Блок изменен');
    }

    public function destroy($id)
    {
        // dd($id);
        $studentsPages = StudentsPage::find($id);
        $content = Translate::find($studentsPages->tab_name);
        $content->delete();
        $studentsPages->delete();

        return redirect('admin/students')->with('flash_message', 'Блок удален');
    }
}
