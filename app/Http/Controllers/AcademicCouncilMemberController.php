<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCouncilMember;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class AcademicCouncilMemberController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $academicCouncilMember = AcademicCouncilMember::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $academicCouncilMember = AcademicCouncilMember::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('academicCouncilMember.index', compact('academicCouncilMember', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('academicCouncilMember.create');
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

        $description = new Translate();
        $description->ru = $requestData['description']['ru'];
        $description->en = $requestData['description']['en'];
        $description->kz = $requestData['description']['kz'];
        $description->save();
        $descriptionId = $description->id;

        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;


        $academicCouncilMember= new AcademicCouncilMember();
        $academicCouncilMember->description = $descriptionId;
        $academicCouncilMember->name = $nameId;
        $academicCouncilMember->save();

        return redirect('admin/academicCouncilMember')->with('flash_message', 'Блок добавлен');
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
        $academicCouncilMember = AcademicCouncilMember::findOrFail($id);
        $translatedName = Translate::findOrFail($academicCouncilMember->name);
        $translatedDescription = Translate::findOrFail($academicCouncilMember->description);
        $translatedData['name'] = $translatedName;
        $translatedData['description'] = $translatedDescription;
        return view('academicCouncilMember.show', compact('academicCouncilMember', 'translatedData'));
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
        $academicCouncilMember = AcademicCouncilMember::findOrFail($id);
        $translatedName = Translate::findOrFail($academicCouncilMember->name);
        $translatedDescription = Translate::findOrFail($academicCouncilMember->description);
        $translatedData['name'] = $translatedName;
        $translatedData['description'] = $translatedDescription;
        return view('academicCouncilMember.edit', compact('academicCouncilMember', 'translatedData'));
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
        $academicCouncilMember = AcademicCouncilMember::findOrFail($id);

        $description = Translate::find($academicCouncilMember->description);
        $description->ru = $requestData['description']['ru'];
        $description->en = $requestData['description']['en'];
        $description->kz = $requestData['description']['kz'];
        $description->update();

        $name = Translate::find($academicCouncilMember->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $academicCouncilMember->update();

        return redirect('admin/academicCouncilMember')->with('flash_message', 'Блок изменен');
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
        $academicCouncilMember = AcademicCouncilMember::find($id);
        $description = Translate::find($academicCouncilMember->description);
        $name = Translate::find($academicCouncilMember->name);
        $description->delete();
        $name->delete();
        $academicCouncilMember->delete();

        return redirect('admin/academicCouncilMember')->with('flash_message', 'Блок удален');
    }
}
