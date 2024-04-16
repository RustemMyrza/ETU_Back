<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RectorsBlogPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class RectorsBlogPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $rectorsBlogPage = RectorsBlogPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $rectorsBlogPage = RectorsBlogPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('rectorsBlogPage.index', compact('rectorsBlogPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('rectorsBlogPage.create');
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
        if ($request->hasFile('image')) {
            $path = $this->uploadImage($request->file('image'));
        }
        $content = new Translate();
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->save();
        $contentId = $content->id;

        $title = new Translate();
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->save();
        $titleId = $title->id;


        $rectorsBlogPage= new RectorsBlogPage();
        $rectorsBlogPage->title = $titleId;
        $rectorsBlogPage->content = $contentId;
        $rectorsBlogPage->image = $path ?? null;
        $rectorsBlogPage->save();

        return redirect('admin/rectorsBlogPage')->with('flash_message', 'Блок добавлен');
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
        $rectorsBlogPage = RectorsBlogPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($rectorsBlogPage->title);
        $translatedContent = Translate::findOrFail($rectorsBlogPage->content);
        $image = Translate::findOrFail($rectorsBlogPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('rectorsBlogPage.show', compact('rectorsBlogPage', 'translatedData'));
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
        $rectorsBlogPage = RectorsBlogPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($rectorsBlogPage->title);
        $translatedContent = Translate::findOrFail($rectorsBlogPage->content);
        $image = Translate::findOrFail($rectorsBlogPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('rectorsBlogPage.edit', compact('rectorsBlogPage', 'translatedData'));
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
        $rectorsBlogPage = RectorsBlogPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($rectorsBlogPage->image != null) {
                Storage::disk('static')->delete($rectorsBlogPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $rectorsBlogPage->image = $path;
        }

        $content = Translate::find($rectorsBlogPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($rectorsBlogPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $rectorsBlogPage->update();

        return redirect('admin/rectorsBlogPage')->with('flash_message', 'Блок изменен');
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
        $rectorsBlogPage = RectorsBlogPage::find($id);
        if ($rectorsBlogPage->image != null) {
            unlink($rectorsBlogPage->image);
        }
        $content = Translate::find($rectorsBlogPage->content);
        $content->delete();
        $rectorsBlogPage->delete();

        return redirect('admin/rectorsBlogPage')->with('flash_message', 'Блок удален');
    }
}
