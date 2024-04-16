<?php

namespace App\Http\Controllers;

use App\Models\AboutUsPage;
use App\Models\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $aboutUs = AboutUsPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $aboutUs = AboutUsPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('aboutUsPage.index', compact('aboutUs', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('aboutUsPage.create');
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


        $aboutUs= new AboutUsPage();
        $aboutUs->title = $titleId;
        $aboutUs->content = $contentId;
        $aboutUs->image = $path ?? null;
        $aboutUs->save();

        return redirect('admin/aboutUs')->with('flash_message', 'Блок добавлен');
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
        $aboutUs = AboutUsPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($aboutUs->title);
        $translatedContent = Translate::findOrFail($aboutUs->content);
        $image = Translate::findOrFail($aboutUs->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('aboutUsPage.show', compact('aboutUs', 'translatedData'));
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
        $aboutUs = AboutUsPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($aboutUs->title);
        $translatedContent = Translate::findOrFail($aboutUs->content);
        $image = Translate::findOrFail($aboutUs->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('aboutUsPage.edit', compact('aboutUs', 'translatedData'));
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
        $aboutUs = AboutUsPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($aboutUs->image != null) {
                Storage::disk('static')->delete($aboutUs->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $aboutUs->image = $path;
        }

        $content = Translate::find($aboutUs->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($aboutUs->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $aboutUs->update();

        return redirect('admin/aboutUs')->with('flash_message', 'Блок изменен');
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
        $aboutUs = AboutUsPage::find($id);
        if ($aboutUs->image != null) {
            unlink($aboutUs->image);
        }
        $content = Translate::find($aboutUs->content);
        $content->delete();
        $aboutUs->delete();

        return redirect('admin/aboutUs')->with('flash_message', 'Блок удален');
    }
}
