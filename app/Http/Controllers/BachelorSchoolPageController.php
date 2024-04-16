<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSchool;
use App\Models\BachelorSchoolPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class BachelorSchoolPageController extends Controller
{
    public function index(Request $request, $schoolId)
    {
        // dd($schoolId);
        $schoolName = BachelorSchool::findOrFail($schoolId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $bachelorSchoolPage = BachelorSchoolPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $bachelorSchoolPage = BachelorSchoolPage::where('school_id', $schoolId)
                ->latest()
                ->paginate($perPage);
        }
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('bachelorSchoolPage.index', compact('bachelorSchoolPage', 'translatesData', 'schoolId', 'schoolName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($schoolId)
    {
        // dd($schoolId);
        return view('bachelorSchoolPage.create', compact('schoolId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $schoolId)
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
        $title = new Translate();
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->save();
        $titleId = $title->id;

        $content = new Translate();
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->save();
        $contentId = $content->id;

        $bachelorSchoolPage= new BachelorSchoolPage();
        $bachelorSchoolPage->title = $titleId;
        $bachelorSchoolPage->content = $contentId;
        $bachelorSchoolPage->image = $path ?? null;
        $bachelorSchoolPage->school_id = $schoolId;
        $bachelorSchoolPage->save();

        return redirect('admin/bachelorSchool/' . $schoolId . '/page')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($schoolId, $id)
    {
        $bachelorSchoolPage = BachelorSchoolPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($bachelorSchoolPage->title);
        $translatedContent = Translate::findOrFail($bachelorSchoolPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        return view('bachelorSchoolPage.show', compact('bachelorSchoolPage', 'translatedData', 'schoolId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($schoolId, $id)
    {
        $bachelorSchoolPage = BachelorSchoolPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($bachelorSchoolPage->title);
        $translatedContent = Translate::findOrFail($bachelorSchoolPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        return view('bachelorSchoolPage.edit', compact('bachelorSchoolPage', 'translatedData', 'schoolId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $schoolId, $id)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);
        $requestData = $request->all();
        $bachelorSchoolPage = BachelorSchoolPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($bachelorSchoolPage->image != null) {
                Storage::disk('static')->delete($bachelorSchoolPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $bachelorSchoolPage->image = $path;
        }
        $title = Translate::find($bachelorSchoolPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $content = Translate::find($bachelorSchoolPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();
        $bachelorSchoolPage->update();

        return redirect('admin/bachelorSchool/' . $schoolId . '/page')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($schoolId, $id)
    {
        $bachelorSchoolPage = BachelorSchoolPage::find($id);
        if ($bachelorSchoolPage->image != null) {
            unlink($bachelorSchoolPage->image);
        }
        $title = Translate::find($bachelorSchoolPage->title);
        $content = Translate::find($bachelorSchoolPage->content);
        $title->delete();
        $content->delete();
        $bachelorSchoolPage->delete();

        return redirect('admin/bachelorSchool/' . $schoolId . '/page')->with('flash_message', 'Блок удален');
    }
}
