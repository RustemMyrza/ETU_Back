<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LincolnUniversityPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class LincolnUniversityPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $lincolnUniversityPage = LincolnUniversityPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $lincolnUniversityPage = LincolnUniversityPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('lincolnUniversityPage.index', compact('lincolnUniversityPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('lincolnUniversityPage.create');
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


        $lincolnUniversityPage= new LincolnUniversityPage();
        $lincolnUniversityPage->title = $titleId;
        $lincolnUniversityPage->content = $contentId;
        $lincolnUniversityPage->image = $path ?? null;
        $lincolnUniversityPage->save();

        return redirect('admin/lincolnUniversityPage')->with('flash_message', 'Блок добавлен');
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
        $lincolnUniversityPage = LincolnUniversityPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($lincolnUniversityPage->title);
        $translatedContent = Translate::findOrFail($lincolnUniversityPage->content);
        $image = Translate::findOrFail($lincolnUniversityPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('lincolnUniversityPage.show', compact('lincolnUniversityPage', 'translatedData'));
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
        $lincolnUniversityPage = LincolnUniversityPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($lincolnUniversityPage->title);
        $translatedContent = Translate::findOrFail($lincolnUniversityPage->content);
        $image = Translate::findOrFail($lincolnUniversityPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('lincolnUniversityPage.edit', compact('lincolnUniversityPage', 'translatedData'));
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
        $lincolnUniversityPage = LincolnUniversityPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($lincolnUniversityPage->image != null) {
                unlink($lincolnUniversityPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $lincolnUniversityPage->image = $path;
        }

        $content = Translate::find($lincolnUniversityPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($lincolnUniversityPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $lincolnUniversityPage->update();

        return redirect('admin/lincolnUniversityPage')->with('flash_message', 'Блок изменен');
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
        $lincolnUniversityPage = LincolnUniversityPage::find($id);
        if ($lincolnUniversityPage->image != null) {
            unlink($lincolnUniversityPage->image);
        }
        $content = Translate::find($lincolnUniversityPage->content);
        $content->delete();
        $lincolnUniversityPage->delete();

        return redirect('admin/lincolnUniversityPage')->with('flash_message', 'Блок удален');
    }
}
