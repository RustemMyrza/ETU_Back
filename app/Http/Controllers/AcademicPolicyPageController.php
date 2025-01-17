<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicPolicyPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class AcademicPolicyPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $academicPolicyPage = AcademicPolicyPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $academicPolicyPage = AcademicPolicyPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('academicPolicyPage.index', compact('academicPolicyPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('academicPolicyPage.create');
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


        $academicPolicyPage= new AcademicPolicyPage();
        $academicPolicyPage->title = $titleId;
        $academicPolicyPage->content = $contentId;
        $academicPolicyPage->image = $path ?? null;
        $academicPolicyPage->save();

        return redirect('admin/academicPolicyPage')->with('flash_message', 'Блок добавлен');
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
        $academicPolicyPage = AcademicPolicyPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($academicPolicyPage->title);
        $translatedContent = Translate::findOrFail($academicPolicyPage->content);
        $image = Translate::findOrFail($academicPolicyPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('academicPolicyPage.show', compact('academicPolicyPage', 'translatedData'));
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
        $academicPolicyPage = AcademicPolicyPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($academicPolicyPage->title);
        $translatedContent = Translate::findOrFail($academicPolicyPage->content);
        $image = Translate::findOrFail($academicPolicyPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('academicPolicyPage.edit', compact('academicPolicyPage', 'translatedData'));
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
        $academicPolicyPage = AcademicPolicyPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($academicPolicyPage->image != null) {
                unlink($academicPolicyPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $academicPolicyPage->image = $path;
        }

        $content = Translate::find($academicPolicyPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($academicPolicyPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $academicPolicyPage->update();

        return redirect('admin/academicPolicyPage')->with('flash_message', 'Блок изменен');
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
        $academicPolicyPage = AcademicPolicyPage::find($id);
        if ($academicPolicyPage->image != null) {
            unlink($academicPolicyPage->image);
        }
        $content = Translate::find($academicPolicyPage->content);
        $title = Translate::find($academicPolicyPage->title);
        $title->delete();
        $content->delete();
        $academicPolicyPage->delete();

        return redirect('admin/academicPolicyPage')->with('flash_message', 'Блок удален');
    }
}
