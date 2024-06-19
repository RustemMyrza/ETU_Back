<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Translate;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 25;
        $discount = Discount::latest()->paginate($perPage);

        return view('discount.index', compact('discount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('discount.create');
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


        $discount= new Discount();
        $discount->name = $nameId;
        $discount->image = $path;
        $discount->save();

        return redirect('admin/discount')->with('flash_message', 'Блок добавлен');
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
        $discount = Discount::findOrFail($id);
        return view('discount.show', compact('discount'));
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
        $discount = Discount::findOrFail($id);
        return view('discount.edit', compact('discount'));
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
        $discount = Discount::findOrFail($id);

        if ($request->hasFile('image')) 
        {
            if ($discount->image != null) {
                // unlink($discount->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $discount->image = $path;
        }
        else
        {
            if ($discount->image != null) {
                // unlink($discount->image);
            }
        }

        $name = Translate::find($discount->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();

        return redirect('admin/discount')->with('flash_message', 'Блок изменен');
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
        $discount = Discount::find($id);
        $name = Translate::find($discount->name);
        $name->delete();
        $discount->delete();
        if ($discount->image != null) 
        {
            // unlink($discount->image);
        }

        return redirect('admin/discount')->with('flash_message', 'Блок удален');
    }
}
