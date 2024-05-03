<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentClubPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class StudentClubPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $studentClubPage = StudentClubPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $studentClubPage = StudentClubPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('studentClubPage.index', compact('studentClubPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('studentClubPage.create');
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


        $studentClubPage= new StudentClubPage();
        $studentClubPage->title = $titleId;
        $studentClubPage->content = $contentId;
        $studentClubPage->image = $path ?? null;
        $studentClubPage->save();

        return redirect('admin/studentClubPage')->with('flash_message', 'Блок добавлен');
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
        $studentClubPage = StudentClubPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($studentClubPage->title);
        $translatedContent = Translate::findOrFail($studentClubPage->content);
        $image = Translate::findOrFail($studentClubPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('studentClubPage.show', compact('studentClubPage', 'translatedData'));
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
        $studentClubPage = StudentClubPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($studentClubPage->title);
        $translatedContent = Translate::findOrFail($studentClubPage->content);
        $image = Translate::findOrFail($studentClubPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('studentClubPage.edit', compact('studentClubPage', 'translatedData'));
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
        $studentClubPage = StudentClubPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($studentClubPage->image != null) {
                unlink($studentClubPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $studentClubPage->image = $path;
        }

        $content = Translate::find($studentClubPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($studentClubPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $studentClubPage->update();

        return redirect('admin/studentClubPage')->with('flash_message', 'Блок изменен');
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
        $studentClubPage = StudentClubPage::find($id);
        if ($studentClubPage->image != null) {
            unlink($studentClubPage->image);
        }
        $content = Translate::find($studentClubPage->content);
        $title = Translate::find($studentClubPage->title);
        $title->delete();
        $content->delete();
        $studentClubPage->delete();

        return redirect('admin/studentClubPage')->with('flash_message', 'Блок удален');
    }
}
