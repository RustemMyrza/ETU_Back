<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BachelorSchool;
use App\Models\BachelorSchoolSpecialty;
use App\Models\BachelorSchoolSpecialtyPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class BachelorSchoolSpecialtyPageController extends Controller
{
    public function index(Request $request, $schoolId, $specialtyId)
    {
        // dd($schoolId);
        $schoolName = BachelorSchool::findOrFail($schoolId)->getName->ru;
        $specialtyName = BachelorSchoolSpecialty::findOrFail($specialtyId)->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $bachelorSpecialtyPage = BachelorSchoolSpecialtyPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $bachelorSpecialtyPage = BachelorSchoolSpecialtyPage::where('specialty_id', $specialtyId)
                ->latest()
                ->paginate($perPage);
        }
        $translatesData = Translate::all();
        // $this->getDataFromTable();
        return view('bachelorSpecialtyPage.index', compact('bachelorSpecialtyPage', 'translatesData', 'specialtyId', 'schoolId', 'schoolName', 'specialtyName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($schoolId, $specialtyId)
    {
        // dd($schoolId);
        return view('bachelorSpecialtyPage.create', compact('schoolId', 'specialtyId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $schoolId, $specialtyId)
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

        $bachelorSpecialtyPage= new BachelorSchoolSpecialtyPage();
        $bachelorSpecialtyPage->title = $titleId;
        $bachelorSpecialtyPage->content = $contentId;
        $bachelorSpecialtyPage->image = $path ?? null;
        $bachelorSpecialtyPage->specialty_id = $specialtyId;
        $bachelorSpecialtyPage->save();

        return redirect('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/page')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($schoolId, $specialtyId, $id)
    {
        $bachelorSpecialtyPage = BachelorSchoolSpecialtyPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($bachelorSpecialtyPage->title);
        $translatedContent = Translate::findOrFail($bachelorSpecialtyPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        return view('bachelorSpecialtyPage.show', compact('bachelorSpecialtyPage', 'translatedData', 'schoolId', 'specialtyId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($schoolId, $specialtyId, $id)
    {
        $bachelorSpecialtyPage = BachelorSchoolSpecialtyPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($bachelorSpecialtyPage->title);
        $translatedContent = Translate::findOrFail($bachelorSpecialtyPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        return view('bachelorSpecialtyPage.edit', compact('bachelorSpecialtyPage', 'translatedData', 'schoolId', 'specialtyId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $schoolId, $specialtyId, $id)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);
        $requestData = $request->all();
        $bachelorSpecialtyPage = BachelorSchoolSpecialtyPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($bachelorSpecialtyPage->image != null) {
                unlink($bachelorSpecialtyPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $bachelorSpecialtyPage->image = $path;
        }
        $title = Translate::find($bachelorSpecialtyPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $content = Translate::find($bachelorSpecialtyPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();
        $bachelorSpecialtyPage->update();

        return redirect('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . 'page')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($schoolId, $specialtyId, $id)
    {
        $bachelorSpecialtyPage = BachelorSchoolSpecialtyPage::find($id);
        if ($bachelorSpecialtyPage->image != null) {
            unlink($bachelorSpecialtyPage->image);
        }
        $title = Translate::find($bachelorSpecialtyPage->title);
        $content = Translate::find($bachelorSpecialtyPage->content);
        $title->delete();
        $content->delete();
        $bachelorSpecialtyPage->delete();

        return redirect('admin/bachelorSchool/' . $schoolId . '/specialty/' . $specialtyId . '/page')->with('flash_message', 'Блок удален');
    }
}
