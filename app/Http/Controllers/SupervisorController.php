<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use App\Models\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupervisorController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $supervisor = Supervisor::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $supervisor = Supervisor::latest()->paginate($perPage);
            $translatedData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('supervisor.index', compact('supervisor', 'translatedData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('supervisor.create');
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ],
            [
                'image.required' => 'Изображение для блока обязательно',
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 10МБ'
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

        $position = new Translate();
        $position->ru = $requestData['position']['ru'];
        $position->en = $requestData['position']['en'];
        $position->kz = $requestData['position']['kz'];
        $position->save();
        $positionId = $position->id;

        $address = new Translate();
        $address->ru = $requestData['address']['ru'];
        $address->en = $requestData['address']['en'];
        $address->kz = $requestData['address']['kz'];
        $address->save();
        $addressId = $address->id;

        $supervisor= new Supervisor();
        $supervisor->name = $nameId;
        $supervisor->position = $positionId;
        $supervisor->email = $requestData['email'];
        $supervisor->phone = $requestData['phone'];
        $supervisor->address = $addressId;
        $supervisor->image = $path ?? null;
        $supervisor->save();

        return redirect('admin/supervisor')->with('flash_message', 'Блок добавлен');
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
        $supervisor = Supervisor::findOrFail($id);
        $translatedName = Translate::findOrFail($supervisor->name);
        $translatedPosition = Translate::findOrFail($supervisor->position);
        $translatedAddress = Translate::findOrFail($supervisor->address);
        $translatedData['name'] = $translatedName;
        $translatedData['position'] = $translatedPosition;
        $translatedData['address'] = $translatedAddress;
        return view('supervisor.show', compact('supervisor', 'translatedData'));
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
        $supervisor = Supervisor::findOrFail($id);
        $translatedName = Translate::findOrFail($supervisor->name);
        $translatedPosition = Translate::findOrFail($supervisor->position);
        $translatedAddress = Translate::findOrFail($supervisor->address);
        $translatedData['name'] = $translatedName;
        $translatedData['position'] = $translatedPosition;
        $translatedData['address'] = $translatedAddress;
        return view('supervisor.edit', compact('supervisor', 'translatedData'));
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
        $supervisor = Supervisor::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($supervisor->image != null) {
                unlink($supervisor->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $supervisor->image = $path;
        }

        $supervisor->email = $requestData['email'];
        $supervisor->phone = $requestData['phone'];
        

        $name = Translate::find($supervisor->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        $position = Translate::find($supervisor->position);
        $position->ru = $requestData['position']['ru'];
        $position->en = $requestData['position']['en'];
        $position->kz = $requestData['position']['kz'];
        $position->update();

        $address = Translate::find($supervisor->address);
        $address->ru = $requestData['address']['ru'];
        $address->en = $requestData['address']['en'];
        $address->kz = $requestData['address']['kz'];
        $address->update();

        $supervisor->update();

        return redirect('admin/supervisor')->with('flash_message', 'Блок изменен');
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
        $supervisor = Supervisor::findOrFail($id);
        if ($supervisor->image != null) {
            unlink($supervisor->image);
        }
        $name = Translate::findOrFail($supervisor->name);
        $position = Translate::findOrFail($supervisor->position);
        $address = Translate::findOrFail($supervisor->address);
        $name->delete();
        $position->delete();
        $address->delete();
        $supervisor->delete();

        return redirect('admin/supervisor')->with('flash_message', 'Блок удален');
    }
}
