<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EthicsCodePage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class EthicsCodePageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $ethicsCodePage = EthicsCodePage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $ethicsCodePage = EthicsCodePage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('ethicsCodePage.index', compact('ethicsCodePage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('ethicsCodePage.create');
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


        $ethicsCodePage= new EthicsCodePage();
        $ethicsCodePage->title = $titleId;
        $ethicsCodePage->content = $contentId;
        $ethicsCodePage->image = $path ?? null;
        $ethicsCodePage->save();

        return redirect('admin/ethicsCodePage')->with('flash_message', 'Блок добавлен');
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
        $ethicsCodePage = EthicsCodePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($ethicsCodePage->title);
        $translatedContent = Translate::findOrFail($ethicsCodePage->content);
        $image = Translate::findOrFail($ethicsCodePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('ethicsCodePage.show', compact('ethicsCodePage', 'translatedData'));
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
        $ethicsCodePage = EthicsCodePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($ethicsCodePage->title);
        $translatedContent = Translate::findOrFail($ethicsCodePage->content);
        $image = Translate::findOrFail($ethicsCodePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('ethicsCodePage.edit', compact('ethicsCodePage', 'translatedData'));
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
        $ethicsCodePage = EthicsCodePage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($ethicsCodePage->image != null) {
                unlink($ethicsCodePage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $ethicsCodePage->image = $path;
        }

        $content = Translate::find($ethicsCodePage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($ethicsCodePage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $ethicsCodePage->update();

        return redirect('admin/ethicsCodePage')->with('flash_message', 'Блок изменен');
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
        $ethicsCodePage = EthicsCodePage::find($id);
        if ($ethicsCodePage->image != null) {
            unlink($ethicsCodePage->image);
        }
        $content = Translate::find($ethicsCodePage->content);
        $content->delete();
        $ethicsCodePage->delete();

        return redirect('admin/ethicsCodePage')->with('flash_message', 'Блок удален');
    }
}
