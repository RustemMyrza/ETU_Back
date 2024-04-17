<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelUpPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class LevelUpPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $levelUpPage = LevelUpPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $levelUpPage = LevelUpPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('levelUpPage.index', compact('levelUpPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('levelUpPage.create');
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


        $levelUpPage= new LevelUpPage();
        $levelUpPage->title = $titleId;
        $levelUpPage->content = $contentId;
        $levelUpPage->image = $path ?? null;
        $levelUpPage->save();

        return redirect('admin/levelUpPage')->with('flash_message', 'Блок добавлен');
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
        $levelUpPage = LevelUpPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($levelUpPage->title);
        $translatedContent = Translate::findOrFail($levelUpPage->content);
        $image = Translate::findOrFail($levelUpPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('levelUpPage.show', compact('levelUpPage', 'translatedData'));
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
        $levelUpPage = LevelUpPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($levelUpPage->title);
        $translatedContent = Translate::findOrFail($levelUpPage->content);
        $image = Translate::findOrFail($levelUpPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('levelUpPage.edit', compact('levelUpPage', 'translatedData'));
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
        $levelUpPage = LevelUpPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($levelUpPage->image != null) {
                unlink($levelUpPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $levelUpPage->image = $path;
        }

        $content = Translate::find($levelUpPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($levelUpPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $levelUpPage->update();

        return redirect('admin/levelUpPage')->with('flash_message', 'Блок изменен');
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
        $levelUpPage = LevelUpPage::find($id);
        if ($levelUpPage->image != null) {
            unlink($levelUpPage->image);
        }
        $content = Translate::find($levelUpPage->content);
        $content->delete();
        $levelUpPage->delete();

        return redirect('admin/levelUpPage')->with('flash_message', 'Блок удален');
    }
}
