<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MastersSpecialtyPage;
use App\Models\MastersSpecialty;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MastersSpecialtyPageController extends Controller
{
    public function index(Request $request, $mastersSpecialtyId)
    {
        $mastersSpecialtyName = MastersSpecialty::findOrFail($mastersSpecialtyId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $mastersSpecialtyPage = MastersSpecialtyPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $mastersSpecialtyPage = MastersSpecialtyPage::where('parent_id', $mastersSpecialtyId)
                ->latest()
                ->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('mastersSpecialtyPage.index', compact('mastersSpecialtyPage', 'translatesData', 'mastersSpecialtyId', 'mastersSpecialtyName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($mastersSpecialtyId)
    {
        // dd($newsId);
        return view('mastersSpecialtyPage.create', compact('mastersSpecialtyId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $mastersSpecialtyId)
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


        $mastersSpecialtyPage= new MastersSpecialtyPage();
        $mastersSpecialtyPage->title = $titleId;
        $mastersSpecialtyPage->content = $contentId;
        $mastersSpecialtyPage->image = $path ?? null;
        $mastersSpecialtyPage->parent_id = $mastersSpecialtyId;
        $mastersSpecialtyPage->save();

        return redirect('admin/mastersSpecialty/' . $mastersSpecialtyId . '/page')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($mastersSpecialtyId, $id)
    {
        $mastersSpecialtyPage = MastersSpecialtyPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($mastersSpecialtyPage->title);
        $translatedContent = Translate::findOrFail($mastersSpecialtyPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $mastersSpecialtyPage->image;
        return view('mastersSpecialtyPage.show', compact('mastersSpecialtyPage', 'translatedData', 'mastersSpecialtyId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($mastersSpecialtyId, $id)
    {
        $mastersSpecialtyPage = MastersSpecialtyPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($mastersSpecialtyPage->title);
        $translatedContent = Translate::findOrFail($mastersSpecialtyPage->content);
        $image = Translate::findOrFail($mastersSpecialtyPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('mastersSpecialtyPage.edit', compact('mastersSpecialtyPage', 'translatedData', 'mastersSpecialtyId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id, $mastersSpecialtyId)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        $mastersSpecialtyPage = MastersSpecialtyPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($mastersSpecialtyPage->image != null) {
                unlink($mastersSpecialtyPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $mastersSpecialtyPage->image = $path;
        }

        $content = Translate::find($mastersSpecialtyPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($mastersSpecialtyPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $mastersSpecialtyPage->update();

        return redirect('admin/mastersSpecialty/' . $mastersSpecialtyId . '/page')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($mastersSpecialtyId, $id)
    {
        $mastersSpecialtyPage = MastersSpecialtyPage::find($id);
        if ($mastersSpecialtyPage->image != null) {
            unlink($mastersSpecialtyPage->image);
        }

        $content = Translate::find($mastersSpecialtyPage->content);
        $title = Translate::find($mastersSpecialtyPage->title);
        $title->delete();
        $content->delete();
        $mastersSpecialtyPage->delete();

        return redirect('admin/mastersSpecialty/' . $mastersSpecialtyId . '/content')->with('flash_message', 'Блок удален');
    }
}
