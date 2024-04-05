<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSchoolEducator;
use App\Models\BachelorSchool;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class BachelorSchoolEducatorController extends Controller
{
    public function index(Request $request, $schoolId)
    {
        // dd($schoolId);
        $schoolName = BachelorSchool::findOrFail($schoolId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $bachelorSchoolEducator = BachelorSchoolEducator::where('name', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $bachelorSchoolEducator = BachelorSchoolEducator::where('school_id', $schoolId)
                ->latest()
                ->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('bachelorSchoolEducator.index', compact('bachelorSchoolEducator', 'translatesData', 'schoolId', 'schoolName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($schoolId)
    {
        // dd($schoolId);
        return view('bachelorSchoolEducator.create', compact('schoolId'));
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
        // dd($request->all());

        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.required' => 'Изображение для блока обязательно',
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'));
        }
        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;

        if ($requestData['position']['ru'])
        {
            $position = new Translate();
            $position->ru = $requestData['position']['ru'];
            $position->en = $requestData['position']['en'];
            $position->kz = $requestData['position']['kz'];
            $position->save();
            $positionId = $position->id;
        }

        $bachelorSchoolEducator= new BachelorSchoolEducator();
        $bachelorSchoolEducator->name = $nameId;
        $bachelorSchoolEducator->position = $positionId;
        $bachelorSchoolEducator->image = $path ?? null;
        $bachelorSchoolEducator->school_id = $schoolId;
        $bachelorSchoolEducator->save();

        return redirect('admin/bachelorSchool/' . $schoolId . '/educator')->with('flash_message', 'Блок добавлен');
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
        $bachelorSchoolEducator = BachelorSchoolEducator::findOrFail($id);
        $translatedName = Translate::findOrFail($bachelorSchoolEducator->name);
        $translatedPosition = Translate::findOrFail($bachelorSchoolEducator->position);
        $translatedData['name'] = $translatedName;
        $translatedData['position'] = $translatedPosition;
        return view('bachelorSchoolEducator.show', compact('bachelorSchoolEducator', 'translatedData', 'schoolId'));
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
        $bachelorSchoolEducator = BachelorSchoolEducator::findOrFail($id);
        $translatedName = Translate::findOrFail($bachelorSchoolEducator->name);
        $translatedPosition = Translate::findOrFail($bachelorSchoolEducator->position);
        $translatedData['name'] = $translatedName;
        $translatedData['position'] = $translatedPosition;
        return view('bachelorSchoolEducator.edit', compact('bachelorSchoolEducator', 'translatedData', 'schoolId'));
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);
        $requestData = $request->all();
        $bachelorSchoolEducator = BachelorSchoolEducator::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($bachelorSchoolEducator->image != null) {
                Storage::disk('static')->delete($bachelorSchoolEducator->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $bachelorSchoolEducator->image = $path;
        }
        $name = Translate::find($bachelorSchoolEducator->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $position = Translate::find($bachelorSchoolEducator->position);
        $position->ru = $requestData['position']['ru'];
        $position->en = $requestData['position']['en'];
        $position->kz = $requestData['position']['kz'];
        $position->update();

        $bachelorSchoolEducator->update();

        return redirect('admin/bachelorSchool/' . $schoolId . '/educator')->with('flash_message', 'Блок изменен');
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
        $bachelorSchoolEducator = BachelorSchoolEducator::find($id);
        if ($bachelorSchoolEducator->image != null) {
            Storage::disk('static')->delete($bachelorSchoolEducator->image);
        }
        $name = Translate::find($bachelorSchoolEducator->name);
        $position = Translate::find($bachelorSchoolEducator->position);
        $name->delete();
        $position->delete();
        $bachelorSchoolEducator->delete();

        return redirect('admin/bachelorSchool/' . $schoolId . '/educator')->with('flash_message', 'Блок удален');
    }
}
