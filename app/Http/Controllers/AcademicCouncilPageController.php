<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicCouncilPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class AcademicCouncilPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $academicCouncilPage = AcademicCouncilPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $academicCouncilPage = AcademicCouncilPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('academicCouncilPage.index', compact('academicCouncilPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('academicCouncilPage.create');
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


        $academicCouncilPage= new AcademicCouncilPage();
        $academicCouncilPage->title = $titleId;
        $academicCouncilPage->content = $contentId;
        $academicCouncilPage->image = $path ?? null;
        $academicCouncilPage->save();

        return redirect('admin/academicCouncilPage')->with('flash_message', 'Блок добавлен');
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
        $academicCouncilPage = AcademicCouncilPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($academicCouncilPage->title);
        $translatedContent = Translate::findOrFail($academicCouncilPage->content);
        $image = Translate::findOrFail($academicCouncilPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('academicCouncilPage.show', compact('academicCouncilPage', 'translatedData'));
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
        $academicCouncilPage = AcademicCouncilPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($academicCouncilPage->title);
        $translatedContent = Translate::findOrFail($academicCouncilPage->content);
        $image = Translate::findOrFail($academicCouncilPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('academicCouncilPage.edit', compact('academicCouncilPage', 'translatedData'));
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
        $academicCouncilPage = AcademicCouncilPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($academicCouncilPage->image != null) {
                Storage::disk('static')->delete($academicCouncilPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $academicCouncilPage->image = $path;
        }

        $content = Translate::find($academicCouncilPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($academicCouncilPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $academicCouncilPage->update();

        return redirect('admin/academicCouncilPage')->with('flash_message', 'Блок изменен');
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
        $academicCouncilPage = AcademicCouncilPage::find($id);
        if ($academicCouncilPage->image != null) {
            unlink($academicCouncilPage->image);
        }
        $content = Translate::find($academicCouncilPage->content);
        $content->delete();
        $academicCouncilPage->delete();

        return redirect('admin/academicCouncilPage')->with('flash_message', 'Блок удален');
    }
}
