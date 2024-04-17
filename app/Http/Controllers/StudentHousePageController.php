<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentHousePage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class StudentHousePageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $studentHousePage = StudentHousePage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $studentHousePage = StudentHousePage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('studentHousePage.index', compact('studentHousePage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('studentHousePage.create');
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


        $studentHousePage= new StudentHousePage();
        $studentHousePage->title = $titleId;
        $studentHousePage->content = $contentId;
        $studentHousePage->image = $path ?? null;
        $studentHousePage->save();

        return redirect('admin/studentHousePage')->with('flash_message', 'Блок добавлен');
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
        $studentHousePage = StudentHousePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($studentHousePage->title);
        $translatedContent = Translate::findOrFail($studentHousePage->content);
        $image = Translate::findOrFail($studentHousePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('studentHousePage.show', compact('studentHousePage', 'translatedData'));
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
        $studentHousePage = StudentHousePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($studentHousePage->title);
        $translatedContent = Translate::findOrFail($studentHousePage->content);
        $image = Translate::findOrFail($studentHousePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('studentHousePage.edit', compact('studentHousePage', 'translatedData'));
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
        $studentHousePage = StudentHousePage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($studentHousePage->image != null) {
                unlink($studentHousePage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $studentHousePage->image = $path;
        }

        $content = Translate::find($studentHousePage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($studentHousePage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $studentHousePage->update();

        return redirect('admin/studentHousePage')->with('flash_message', 'Блок изменен');
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
        $studentHousePage = StudentHousePage::find($id);
        if ($studentHousePage->image != null) {
            unlink($studentHousePage->image);
        }
        $content = Translate::find($studentHousePage->content);
        $content->delete();
        $studentHousePage->delete();

        return redirect('admin/studentHousePage')->with('flash_message', 'Блок удален');
    }
}
