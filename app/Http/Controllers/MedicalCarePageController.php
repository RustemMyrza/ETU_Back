<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalCarePage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MedicalCarePageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $medicalCarePage = MedicalCarePage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $medicalCarePage = MedicalCarePage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('medicalCarePage.index', compact('medicalCarePage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('medicalCarePage.create');
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


        $medicalCarePage= new MedicalCarePage();
        $medicalCarePage->title = $titleId;
        $medicalCarePage->content = $contentId;
        $medicalCarePage->image = $path ?? null;
        $medicalCarePage->save();

        return redirect('admin/medicalCarePage')->with('flash_message', 'Блок добавлен');
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
        $medicalCarePage = MedicalCarePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($medicalCarePage->title);
        $translatedContent = Translate::findOrFail($medicalCarePage->content);
        $image = Translate::findOrFail($medicalCarePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('medicalCarePage.show', compact('medicalCarePage', 'translatedData'));
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
        $medicalCarePage = MedicalCarePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($medicalCarePage->title);
        $translatedContent = Translate::findOrFail($medicalCarePage->content);
        $image = Translate::findOrFail($medicalCarePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('medicalCarePage.edit', compact('medicalCarePage', 'translatedData'));
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
        $medicalCarePage = MedicalCarePage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($medicalCarePage->image != null) {
                Storage::disk('static')->delete($medicalCarePage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $medicalCarePage->image = $path;
        }

        $content = Translate::find($medicalCarePage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($medicalCarePage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $medicalCarePage->update();

        return redirect('admin/medicalCarePage')->with('flash_message', 'Блок изменен');
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
        $medicalCarePage = MedicalCarePage::find($id);
        if ($medicalCarePage->image != null) {
            unlink($medicalCarePage->image);
        }
        $content = Translate::find($medicalCarePage->content);
        $content->delete();
        $medicalCarePage->delete();

        return redirect('admin/medicalCarePage')->with('flash_message', 'Блок удален');
    }
}
