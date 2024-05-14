<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSchoolSpecialty;
use App\Models\BachelorSchoolSpecialtyPage;
use App\Models\BachelorSpecialtyDocument;
use App\Models\BachelorSchool;
use Illuminate\Support\Facades\Storage;
use App\Models\Translate;

class BachelorSchoolSpecialtyController extends Controller
{
    public function index(Request $request, $schoolId)
    {
        // dd($schoolId);
        $schoolName = BachelorSchool::findOrFail($schoolId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $bachelorSchoolSpecialty = BachelorSchoolSpecialty::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $bachelorSchoolSpecialty = BachelorSchoolSpecialty::where('school_id', $schoolId)
                ->latest()
                ->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('bachelorSchoolSpecialty.index', compact('bachelorSchoolSpecialty', 'translatesData', 'schoolId', 'schoolName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($schoolId)
    {
        // dd($schoolId);
        return view('bachelorSchoolSpecialty.create', compact('schoolId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $schoolId)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ],
        [
            'image.required' => 'Изображение для блока обязательно',
            'image.mimes' => 'Проверьте формат изображения',
            'image.max' => 'Размер файла не может превышать 10МБ'
        ]);
        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'));
        }
        $requestData = $request->all();

        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;

        $bachelorSchoolSpecialty= new BachelorSchoolSpecialty();
        $bachelorSchoolSpecialty->name = $nameId;
        $bachelorSchoolSpecialty->image = $path ?? null;
        $bachelorSchoolSpecialty->school_id = $schoolId;
        $bachelorSchoolSpecialty->save();

        return redirect('admin/bachelorSchool/' . $schoolId . '/specialty')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($schoolId, $id)
    {
        $bachelorSchoolSpecialty = BachelorSchoolSpecialty::findOrFail($id);
        $translatedName = Translate::findOrFail($bachelorSchoolSpecialty->name);
        $translatedData['name'] = $translatedName;
        return view('bachelorSchoolSpecialty.show', compact('bachelorSchoolSpecialty', 'translatedData', 'schoolId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($schoolId, $id)
    {
        $bachelorSchoolSpecialty = BachelorSchoolSpecialty::findOrFail($id);
        $translatedName = Translate::findOrFail($bachelorSchoolSpecialty->name);
        $translatedData['name'] = $translatedName;
        return view('bachelorSchoolSpecialty.edit', compact('bachelorSchoolSpecialty', 'translatedData', 'schoolId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $schoolId, $id)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 10МБ'
            ]);

        $requestData = $request->all();
        $bachelorSchoolSpecialty = BachelorSchoolSpecialty::findOrFail($id);

        if ($request->hasFile('image')) 
        {
            if ($bachelorSchoolSpecialty->image != null) {
                unlink($bachelorSchoolSpecialty->image);
            }
            $path = $this->uploadImage($request->file('image'));
        }
        else
        {
            if ($bachelorSchoolSpecialty->image != null) {
                unlink($bachelorSchoolSpecialty->image);
            }
        }

        $name = Translate::find($bachelorSchoolSpecialty->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $bachelorSchoolSpecialty->image = $path ?? null;
        $bachelorSchoolSpecialty->update();

        return redirect('admin/bachelorSchool/' . $schoolId . '/specialty')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($schoolId, $id)
    {
        $bachelorSchoolSpecialty = BachelorSchoolSpecialty::find($id);

        $page = BachelorSchoolSpecialtyPage::where('specialty_id', $id)->get();
        $documents = BachelorSpecialtyDocument::where('specialty_id', $id)->get();


        if (count($page) > 0)
        {
            foreach ($page as $item)
            {
                if ($item->title)
                {
                    $title = Translate::findOrFail($item->title);
                    $title->delete();
                }
                if ($item->content)
                {
                    $content = Translate::findOrFail($item->content);
                    $content->delete();
                }
                if ($item->image != null) {
                    unlink($item->image);
                }
                $item->delete();
            }
        }

        if (count($documents) > 0)
        {
            foreach ($documents as $item)
            {
                if ($item->name)
                {
                    $name = Translate::findOrFail($item->title);
                    $name->delete();
                }
                if ($item->link != null) {
                    unlink($item->link);
                }
                $item->delete();
            }
        }

        $name = Translate::find($bachelorSchoolSpecialty->name);
        if ($bachelorSchoolSpecialty->image != null) {
            unlink($bachelorSchoolSpecialty->image);
        }
        $name->delete();
        $bachelorSchoolSpecialty->delete();

        return redirect('admin/bachelorSchool/' . $schoolId . '/specialty')->with('flash_message', 'Блок удален');
    }
}
