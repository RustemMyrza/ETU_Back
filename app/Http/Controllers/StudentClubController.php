<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentClub;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class StudentClubController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $studentClub = StudentClub::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $studentClub = StudentClub::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('studentClub.index', compact('studentClub', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('studentClub.create');
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
        $description = new Translate();
        $description->ru = $requestData['description']['ru'];
        $description->en = $requestData['description']['en'];
        $description->kz = $requestData['description']['kz'];
        $description->save();
        $descriptionId = $description->id;

        $studentClub= new StudentClub();
        $studentClub->name = $requestData['name'];
        $studentClub->description = $descriptionId;
        $studentClub->logo = $path ?? null;
        $studentClub->save();

        return redirect('admin/studentClub')->with('flash_message', 'Блок добавлен');
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
        $studentClub = StudentClub::findOrFail($id);
        $translatedDescription = Translate::findOrFail($studentClub->description);
        return view('studentClub.show', compact('studentClub', 'translatedDescription'));
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
        $studentClub = StudentClub::findOrFail($id);
        $translatedDescription = Translate::findOrFail($studentClub->description);
        return view('studentClub.edit', compact('studentClub', 'translatedDescription'));
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
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        $studentClub = StudentClub::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($studentClub->logo != null) {
                unlink($studentClub->logo);
            }
            $path = $this->uploadImage($request->file('image'));
            $studentClub->logo = $path;
        }

        $description = Translate::find($studentClub->description);
        $description->ru = $requestData['description']['ru'];
        $description->en = $requestData['description']['en'];
        $description->kz = $requestData['description']['kz'];
        $description->update();
        $studentClub->name = $requestData['name'];
        $studentClub->update();

        return redirect('admin/studentClub')->with('flash_message', 'Блок изменен');
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
        $studentClub = StudentClub::find($id);
        if ($studentClub->image != null) {
            unlink($studentClub->image);
        }
        $description = Translate::find($studentClub->description);
        $description->delete();
        $studentClub->delete();

        return redirect('admin/studentClub')->with('flash_message', 'Блок удален');
    }
}
