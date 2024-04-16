<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MasterPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $masterPage = MasterPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $masterPage = MasterPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('masterPage.index', compact('masterPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('masterPage.create');
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


        $masterPage= new MasterPage();
        $masterPage->title = $titleId;
        $masterPage->content = $contentId;
        $masterPage->image = $path ?? null;
        $masterPage->save();

        return redirect('admin/masterPage')->with('flash_message', 'Блок добавлен');
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
        $masterPage = MasterPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($masterPage->title);
        $translatedContent = Translate::findOrFail($masterPage->content);
        $image = Translate::findOrFail($masterPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('masterPage.show', compact('masterPage', 'translatedData'));
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
        $masterPage = MasterPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($masterPage->title);
        $translatedContent = Translate::findOrFail($masterPage->content);
        $image = Translate::findOrFail($masterPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('masterPage.edit', compact('masterPage', 'translatedData'));
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
        $masterPage = MasterPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($masterPage->image != null) {
                Storage::disk('static')->delete($masterPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $masterPage->image = $path;
        }

        $content = Translate::find($masterPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($masterPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $masterPage->update();

        return redirect('admin/masterPage')->with('flash_message', 'Блок изменен');
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
        $masterPage = MasterPage::find($id);
        if ($masterPage->image != null) {
            unlink($masterPage->image);
        }
        $content = Translate::find($masterPage->content);
        $content->delete();
        $masterPage->delete();

        return redirect('admin/masterPage')->with('flash_message', 'Блок удален');
    }
}
