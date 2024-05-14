<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MastersSpecialty;
use App\Models\Translate;

class MastersSpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $mastersSpecialty = MastersSpecialty::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $mastersSpecialty = MastersSpecialty::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('mastersSpecialty.index', compact('mastersSpecialty', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('mastersSpecialty.create');
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

        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;


        $mastersSpecialty= new MastersSpecialty();
        $mastersSpecialty->name = $nameId;
        $mastersSpecialty->image = $path;
        $mastersSpecialty->save();

        return redirect('admin/mastersSpecialty')->with('flash_message', 'Блок добавлен');
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
        $mastersSpecialty = MastersSpecialty::findOrFail($id);
        $translatedName = Translate::findOrFail($mastersSpecialty->name);
        $translatedData['name'] = $translatedName;
        return view('mastersSpecialty.show', compact('mastersSpecialty', 'translatedData'));
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
        $mastersSpecialty = MastersSpecialty::findOrFail($id);
        $translatedName = Translate::findOrFail($mastersSpecialty->name);
        $translatedData['name'] = $translatedName;
        return view('mastersSpecialty.edit', compact('mastersSpecialty', 'translatedData'));
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 10МБ'
            ]);

        $requestData = $request->all();
        $mastersSpecialty = MastersSpecialty::findOrFail($id);

        if ($request->hasFile('image')) 
        {
            if ($mastersSpecialty->image != null) {
                unlink($mastersSpecialty->image);
            }
            $path = $this->uploadImage($request->file('image'));
        }
        else
        {
            if ($mastersSpecialty->image != null) {
                unlink($mastersSpecialty->image);
            }
        }

        $name = Translate::find($mastersSpecialty->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();
        $mastersSpecialty->image = $path ?? null;
        $mastersSpecialty->update();

        return redirect('admin/mastersSpecialty')->with('flash_message', 'Блок изменен');
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
        $mastersSpecialty = MastersSpecialty::find($id);
        if ($mastersSpecialty->image != null) {
            unlink($mastersSpecialty->image);
        }
        $name = Translate::find($mastersSpecialty->name);
        $name->delete();
        $mastersSpecialty->delete();

        return redirect('admin/mastersSpecialty')->with('flash_message', 'Блок удален');
    }
}
