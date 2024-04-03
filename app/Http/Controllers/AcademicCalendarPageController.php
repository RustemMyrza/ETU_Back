<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCalendarPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class AcademicCalendarPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $academicCalendarPage = AcademicCalendarPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $academicCalendarPage = AcademicCalendarPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('academicCalendarPage.index', compact('academicCalendarPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('academicCalendarPage.create');
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


        $academicCalendarPage= new AcademicCalendarPage();
        $academicCalendarPage->title = $titleId;
        $academicCalendarPage->content = $contentId;
        $academicCalendarPage->image = $path ?? null;
        $academicCalendarPage->save();

        return redirect('admin/academicCalendarPage')->with('flash_message', 'Блок добавлен');
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
        $academicCalendarPage = AcademicCalendarPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($academicCalendarPage->title);
        $translatedContent = Translate::findOrFail($academicCalendarPage->content);
        $image = Translate::findOrFail($academicCalendarPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('academicCalendarPage.show', compact('academicCalendarPage', 'translatedData'));
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
        $academicCalendarPage = AcademicCalendarPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($academicCalendarPage->title);
        $translatedContent = Translate::findOrFail($academicCalendarPage->content);
        $image = Translate::findOrFail($academicCalendarPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('academicCalendarPage.edit', compact('academicCalendarPage', 'translatedData'));
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
        $academicCalendarPage = AcademicCalendarPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($academicCalendarPage->image != null) {
                Storage::disk('static')->delete($academicCalendarPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $academicCalendarPage->image = $path;
        }

        $content = Translate::find($academicCalendarPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($academicCalendarPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $academicCalendarPage->update();

        return redirect('admin/academicCalendarPage')->with('flash_message', 'Блок изменен');
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
        $academicCalendarPage = AcademicCalendarPage::find($id);
        if ($academicCalendarPage->image != null) {
            Storage::disk('static')->delete($academicCalendarPage->image);
        }
        $content = Translate::find($academicCalendarPage->content);
        $content->delete();
        $academicCalendarPage->delete();

        return redirect('admin/academicCalendarPage')->with('flash_message', 'Блок удален');
    }
}
