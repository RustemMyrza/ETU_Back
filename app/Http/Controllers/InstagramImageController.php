<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InstagramImage;

class InstagramImageController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 25;
        $instagramImage = InstagramImage::latest()->paginate($perPage);
        return view('instagramImage.index', compact('instagramImage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('instagramImage.create');
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
        if (count(InstagramImage::all()) < 4)
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
    
            $instagramImage= new InstagramImage();
            $instagramImage->image = $path ?? null;
            $instagramImage->save();
    
            return redirect('admin/instagramImage')->with('flash_message', 'Блок добавлен');
        }
        else
        {
            return redirect('admin/instagramImage')->with('error', 'Вы не можете добавить больше 4 изображений');
        }
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
        $instagramImage = InstagramImage::findOrFail($id);
        return view('instagramImage.show', compact('instagramImage'));
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
        $instagramImage = InstagramImage::findOrFail($id);
        return view('instagramImage.edit', compact('instagramImage'));
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

        $instagramImage = InstagramImage::findOrFail($id);
        if ($request->hasFile('image')) 
        {
            if ($instagramImage->image != null) {
                unlink($instagramImage->image);
            }
            $path = $this->uploadImage($request->file('image'));
        }
        else
        {
            if ($instagramImage->image != null) {
                unlink($instagramImage->image);
            }
        }

        $instagramImage->image = $path ?? null;
        $instagramImage->update();

        return redirect('admin/instagramImage')->with('flash_message', 'Блок изменен');
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
        $instagramImage = InstagramImage::find($id);
        if ($instagramImage->image != null) {
            unlink($instagramImage->image);
        }
        $instagramImage->delete();

        return redirect('admin/instagramImage')->with('flash_message', 'Блок удален');
    }
}
