<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScienceAboutPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class ScienceAboutPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $scienceAboutPage = ScienceAboutPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $scienceAboutPage = ScienceAboutPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('scienceAboutPage.index', compact('scienceAboutPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('scienceAboutPage.create');
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
        $contentId = $content->id;

        $title = new Translate();
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->save();
        $titleId = $title->id;


        $scienceAboutPage= new ScienceAboutPage();
        $scienceAboutPage->title = $titleId;
        $scienceAboutPage->content = $contentId;
        $scienceAboutPage->image = $path ?? null;
        $scienceAboutPage->save();

        return redirect('admin/scienceAboutPage')->with('flash_message', 'Блок добавлен');
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
        $scienceAboutPage = ScienceAboutPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($scienceAboutPage->title);
        $translatedContent = Translate::findOrFail($scienceAboutPage->content);
        $image = Translate::findOrFail($scienceAboutPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('scienceAboutPage.show', compact('scienceAboutPage', 'translatedData'));
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
        $scienceAboutPage = ScienceAboutPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($scienceAboutPage->title);
        $translatedContent = Translate::findOrFail($scienceAboutPage->content);
        $image = Translate::findOrFail($scienceAboutPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('scienceAboutPage.edit', compact('scienceAboutPage', 'translatedData'));
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
        $scienceAboutPage = ScienceAboutPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($scienceAboutPage->image != null) {
                Storage::disk('static')->delete($scienceAboutPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $scienceAboutPage->image = $path;
        }

        $content = Translate::find($scienceAboutPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($scienceAboutPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $scienceAboutPage->update();

        return redirect('admin/scienceAboutPage')->with('flash_message', 'Блок изменен');
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
        $scienceAboutPage = ScienceAboutPage::find($id);
        if ($scienceAboutPage->image != null) {
            unlink($scienceAboutPage->image);
        }
        $content = Translate::find($scienceAboutPage->content);
        $content->delete();
        $scienceAboutPage->delete();

        return redirect('admin/scienceAboutPage')->with('flash_message', 'Блок удален');
    }
}
