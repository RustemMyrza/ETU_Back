<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LanguageCoursesPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class LanguageCoursesPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $languageCoursesPage = LanguageCoursesPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $languageCoursesPage = LanguageCoursesPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('languageCoursesPage.index', compact('languageCoursesPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('languageCoursesPage.create');
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


        $languageCoursesPage= new LanguageCoursesPage();
        $languageCoursesPage->title = $titleId;
        $languageCoursesPage->content = $contentId;
        $languageCoursesPage->image = $path ?? null;
        $languageCoursesPage->save();

        return redirect('admin/languageCoursesPage')->with('flash_message', 'Блок добавлен');
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
        $languageCoursesPage = LanguageCoursesPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($languageCoursesPage->title);
        $translatedContent = Translate::findOrFail($languageCoursesPage->content);
        $image = Translate::findOrFail($languageCoursesPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('languageCoursesPage.show', compact('languageCoursesPage', 'translatedData'));
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
        $languageCoursesPage = LanguageCoursesPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($languageCoursesPage->title);
        $translatedContent = Translate::findOrFail($languageCoursesPage->content);
        $image = Translate::findOrFail($languageCoursesPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('languageCoursesPage.edit', compact('languageCoursesPage', 'translatedData'));
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
        $languageCoursesPage = LanguageCoursesPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($languageCoursesPage->image != null) {
                unlink($languageCoursesPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $languageCoursesPage->image = $path;
        }

        $content = Translate::find($languageCoursesPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($languageCoursesPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $languageCoursesPage->update();

        return redirect('admin/languageCoursesPage')->with('flash_message', 'Блок изменен');
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
        $languageCoursesPage = LanguageCoursesPage::find($id);
        if ($languageCoursesPage->image != null) {
            unlink($languageCoursesPage->image);
        }
        $content = Translate::find($languageCoursesPage->content);
        $title = Translate::find($languageCoursesPage->title);
        $title->delete();
        $content->delete();
        $languageCoursesPage->delete();

        return redirect('admin/languageCoursesPage')->with('flash_message', 'Блок удален');
    }
}
