<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MajorMinorPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MajorMinorPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $majorMinorPage = MajorMinorPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $majorMinorPage = MajorMinorPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('majorMinorPage.index', compact('majorMinorPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('majorMinorPage.create');
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


        $majorMinorPage= new MajorMinorPage();
        $majorMinorPage->title = $titleId;
        $majorMinorPage->content = $contentId;
        $majorMinorPage->image = $path ?? null;
        $majorMinorPage->save();

        return redirect('admin/majorMinorPage')->with('flash_message', 'Блок добавлен');
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
        $majorMinorPage = MajorMinorPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($majorMinorPage->title);
        $translatedContent = Translate::findOrFail($majorMinorPage->content);
        $image = Translate::findOrFail($majorMinorPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('majorMinorPage.show', compact('majorMinorPage', 'translatedData'));
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
        $majorMinorPage = MajorMinorPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($majorMinorPage->title);
        $translatedContent = Translate::findOrFail($majorMinorPage->content);
        $image = Translate::findOrFail($majorMinorPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('majorMinorPage.edit', compact('majorMinorPage', 'translatedData'));
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
        $majorMinorPage = MajorMinorPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($majorMinorPage->image != null) {
                Storage::disk('static')->delete($majorMinorPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $majorMinorPage->image = $path;
        }

        $content = Translate::find($majorMinorPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($majorMinorPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $majorMinorPage->update();

        return redirect('admin/majorMinorPage')->with('flash_message', 'Блок изменен');
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
        $majorMinorPage = MajorMinorPage::find($id);
        if ($majorMinorPage->image != null) {
            unlink($majorMinorPage->image);
        }
        $content = Translate::find($majorMinorPage->content);
        $content->delete();
        $majorMinorPage->delete();

        return redirect('admin/majorMinorPage')->with('flash_message', 'Блок удален');
    }
}
