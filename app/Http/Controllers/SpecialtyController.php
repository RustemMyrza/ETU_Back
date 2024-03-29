<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Specialty;
use App\Models\Translate;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $specialty = Specialty::where('content', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $specialty = Specialty::latest()->paginate($perPage);
            $translatedData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('specialty.index', compact('specialty', 'translatedData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('specialty.create');
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
        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;

        $specialty= new Specialty();
        $specialty->code = $requestData['code'];
        $specialty->name = $nameId;
        $specialty->save();

        return redirect('admin/specialty')->with('flash_message', 'Блок добавлен');
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
        $specialty = Specialty::findOrFail($id);
        $translatedName = Translate::findOrFail($specialty->name);
        return view('specialty.show', compact('specialty', 'translatedName'));
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
        $specialty = Specialty::findOrFail($id);
        $translatedName = Translate::findOrFail($specialty->name);
        return view('specialty.edit', compact('specialty', 'translatedName'));
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
        $specialty = Specialty::findOrFail($id);

        $specialty->code = $requestData['code'];
        

        $name = Translate::find($specialty->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $specialty->update();

        return redirect('admin/specialty')->with('flash_message', 'Блок изменен');
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
        $specialty = Specialty::findOrFail($id);
        $name = Translate::findOrFail($specialty->name);
        $name->delete();
        $specialty->delete();

        return redirect('admin/specialty')->with('flash_message', 'Блок удален');
    }
}
