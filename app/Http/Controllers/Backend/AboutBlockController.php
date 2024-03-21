<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\AboutBlock;
use Illuminate\Http\Request;
use App\Models\Translate;

class AboutBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $aboutblock = AboutBlock::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $aboutblock = AboutBlock::latest()->paginate($perPage);
        }

        return view('about-block.index', compact('aboutblock'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('about-block.create');
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
        $content = new Translate();
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->save();

        $aboutblock = new AboutBlock();
        $aboutblock->content = $content->id;
        $aboutblock->image = $path ?? null;
        $aboutblock->save();

        return redirect('admin/about-block')->with('flash_message', 'Блок добавлен');
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
        $aboutblock = AboutBlock::findOrFail($id);
        $translatedData = Translate::findOrFail($aboutblock->content)->ru;
        // dd($translatedData);
        return view('about-block.show', compact('aboutblock', 'translatedData'));
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
        $aboutblock = AboutBlock::findOrFail($id);

        return view('about-block.edit', compact('aboutblock'));
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
        $aboutblock = AboutBlock::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($aboutblock->image != null) {
                Storage::disk('static')->delete($aboutblock->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $aboutblock->image = $path;
        }

        $content = Translate::find($aboutblock->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $aboutblock->update();

        return redirect('admin/about-block')->with('flash_message', 'Блок изменен');
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
        $aboutblock = AboutBlock::find($id);
        if ($aboutblock->image != null) {
            Storage::disk('static')->delete($aboutblock->image);
        }
        $content = Translate::find($aboutblock->content);
        $content->delete();
        $aboutblock->delete();

        return redirect('admin/about-block')->with('flash_message', 'Блок удален');
    }
}
