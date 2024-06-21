<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cost;
use App\Models\Translate;

class CostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 25;
        $cost = Cost::latest()->paginate($perPage);

        return view('cost.index', compact('cost'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cost.create');
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


        $cost= new Cost();
        $cost->name = $nameId;
        $cost->image = $path;
        $cost->type = $requestData['type'];
        $cost->save();

        return redirect('admin/cost')->with('flash_message', 'Блок добавлен');
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
        $cost = Cost::findOrFail($id);
        return view('cost.show', compact('cost'));
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
        $cost = Cost::findOrFail($id);
        return view('cost.edit', compact('cost'));
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
        $cost = Cost::findOrFail($id);

        if ($request->hasFile('image')) 
        {
            $path = $this->uploadImage($request->file('image'));
            $cost->image = $path;
        }

        $name = Translate::find($cost->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        return redirect('admin/Cost')->with('flash_message', 'Блок изменен');
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
        $cost = Cost::find($id);
        $name = Translate::find($cost->name);
        $name->delete();
        $cost->delete();
        if ($cost->image != null) 
        {
            unlink($cost->image);
        }

        return redirect('admin/cost')->with('flash_message', 'Блок удален');
    }
}
