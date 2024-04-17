<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternationalStudentsPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class InternationalStudentsPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $internationalStudentsPage = InternationalStudentsPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $internationalStudentsPage = InternationalStudentsPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('internationalStudentsPage.index', compact('internationalStudentsPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('internationalStudentsPage.create');
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


        $internationalStudentsPage= new InternationalStudentsPage();
        $internationalStudentsPage->title = $titleId;
        $internationalStudentsPage->content = $contentId;
        $internationalStudentsPage->image = $path ?? null;
        $internationalStudentsPage->save();

        return redirect('admin/internationalStudentsPage')->with('flash_message', 'Блок добавлен');
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
        $internationalStudentsPage = InternationalStudentsPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($internationalStudentsPage->title);
        $translatedContent = Translate::findOrFail($internationalStudentsPage->content);
        $image = Translate::findOrFail($internationalStudentsPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('internationalStudentsPage.show', compact('internationalStudentsPage', 'translatedData'));
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
        $internationalStudentsPage = InternationalStudentsPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($internationalStudentsPage->title);
        $translatedContent = Translate::findOrFail($internationalStudentsPage->content);
        $image = Translate::findOrFail($internationalStudentsPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('internationalStudentsPage.edit', compact('internationalStudentsPage', 'translatedData'));
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
        $internationalStudentsPage = InternationalStudentsPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($internationalStudentsPage->image != null) {
                unlink($internationalStudentsPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $internationalStudentsPage->image = $path;
        }

        $content = Translate::find($internationalStudentsPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($internationalStudentsPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $internationalStudentsPage->update();

        return redirect('admin/internationalStudentsPage')->with('flash_message', 'Блок изменен');
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
        $internationalStudentsPage = InternationalStudentsPage::find($id);
        if ($internationalStudentsPage->image != null) {
            unlink($internationalStudentsPage->image);
        }
        $content = Translate::find($internationalStudentsPage->content);
        $title = Translate::find($internationalStudentsPage->title);
        $title->delete();
        $content->delete();
        $internationalStudentsPage->delete();

        return redirect('admin/internationalStudentsPage')->with('flash_message', 'Блок удален');
    }
}
