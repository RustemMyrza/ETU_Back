<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PartnersPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class PartnersPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $partnersPage = PartnersPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $partnersPage = PartnersPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('partnersPage.index', compact('partnersPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('partnersPage.create');
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


        $partnersPage= new PartnersPage();
        $partnersPage->title = $titleId;
        $partnersPage->content = $contentId;
        $partnersPage->image = $path ?? null;
        $partnersPage->save();

        return redirect('admin/partnersPage')->with('flash_message', 'Блок добавлен');
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
        $partnersPage = PartnersPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($partnersPage->title);
        $translatedContent = Translate::findOrFail($partnersPage->content);
        $image = Translate::findOrFail($partnersPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('partnersPage.show', compact('partnersPage', 'translatedData'));
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
        $partnersPage = PartnersPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($partnersPage->title);
        $translatedContent = Translate::findOrFail($partnersPage->content);
        $image = Translate::findOrFail($partnersPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('partnersPage.edit', compact('partnersPage', 'translatedData'));
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
        $partnersPage = PartnersPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($partnersPage->image != null) {
                unlink($partnersPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $partnersPage->image = $path;
        }

        $content = Translate::find($partnersPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($partnersPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $partnersPage->update();

        return redirect('admin/partnersPage')->with('flash_message', 'Блок изменен');
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
        $partnersPage = PartnersPage::find($id);
        if ($partnersPage->image != null) {
            unlink($partnersPage->image);
        }
        $content = Translate::find($partnersPage->content);
        $title = Translate::find($partnersPage->title);
        $title->delete();
        $content->delete();
        $partnersPage->delete();

        return redirect('admin/partnersPage')->with('flash_message', 'Блок удален');
    }
}
