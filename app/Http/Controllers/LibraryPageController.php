<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LibraryPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class LibraryPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $libraryPage = LibraryPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $libraryPage = LibraryPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('libraryPage.index', compact('libraryPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('libraryPage.create');
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


        $libraryPage= new LibraryPage();
        $libraryPage->title = $titleId;
        $libraryPage->content = $contentId;
        $libraryPage->image = $path ?? null;
        $libraryPage->save();

        return redirect('admin/libraryPage')->with('flash_message', 'Блок добавлен');
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
        $libraryPage = LibraryPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($libraryPage->title);
        $translatedContent = Translate::findOrFail($libraryPage->content);
        $image = Translate::findOrFail($libraryPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('libraryPage.show', compact('libraryPage', 'translatedData'));
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
        $libraryPage = LibraryPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($libraryPage->title);
        $translatedContent = Translate::findOrFail($libraryPage->content);
        $image = Translate::findOrFail($libraryPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('libraryPage.edit', compact('libraryPage', 'translatedData'));
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
        $libraryPage = LibraryPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($libraryPage->image != null) {
                unlink($libraryPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $libraryPage->image = $path;
        }

        $content = Translate::find($libraryPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($libraryPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $libraryPage->update();

        return redirect('admin/libraryPage')->with('flash_message', 'Блок изменен');
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
        $libraryPage = LibraryPage::find($id);
        if ($libraryPage->image != null) {
            unlink($libraryPage->image);
        }
        $content = Translate::find($libraryPage->content);
        $content->delete();
        $libraryPage->delete();

        return redirect('admin/libraryPage')->with('flash_message', 'Блок удален');
    }
}
