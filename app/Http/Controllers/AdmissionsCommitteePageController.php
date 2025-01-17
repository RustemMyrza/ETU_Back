<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdmissionsCommitteePage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class AdmissionsCommitteePageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $admissionsCommitteePage = AdmissionsCommitteePage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $admissionsCommitteePage = AdmissionsCommitteePage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('admissionsCommitteePage.index', compact('admissionsCommitteePage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admissionsCommitteePage.create');
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


        $admissionsCommitteePage= new AdmissionsCommitteePage();
        $admissionsCommitteePage->title = $titleId;
        $admissionsCommitteePage->content = $contentId;
        $admissionsCommitteePage->image = $path ?? null;
        $admissionsCommitteePage->save();

        return redirect('admin/admissionsCommitteePage')->with('flash_message', 'Блок добавлен');
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
        $admissionsCommitteePage = AdmissionsCommitteePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($admissionsCommitteePage->title);
        $translatedContent = Translate::findOrFail($admissionsCommitteePage->content);
        $image = Translate::findOrFail($admissionsCommitteePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('admissionsCommitteePage.show', compact('admissionsCommitteePage', 'translatedData'));
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
        $admissionsCommitteePage = AdmissionsCommitteePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($admissionsCommitteePage->title);
        $translatedContent = Translate::findOrFail($admissionsCommitteePage->content);
        $image = Translate::findOrFail($admissionsCommitteePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('admissionsCommitteePage.edit', compact('admissionsCommitteePage', 'translatedData'));
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
        $admissionsCommitteePage = AdmissionsCommitteePage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($admissionsCommitteePage->image != null) {
                unlink($admissionsCommitteePage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $admissionsCommitteePage->image = $path;
        }

        $content = Translate::find($admissionsCommitteePage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($admissionsCommitteePage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $admissionsCommitteePage->update();

        return redirect('admin/admissionsCommitteePage')->with('flash_message', 'Блок изменен');
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
        $admissionsCommitteePage = AdmissionsCommitteePage::find($id);
        if ($admissionsCommitteePage->image != null) {
            unlink($admissionsCommitteePage->image);
        }
        $content = Translate::find($admissionsCommitteePage->content);
        $title = Translate::find($admissionsCommitteePage->title);
        $title->delete();
        $content->delete();
        $admissionsCommitteePage->delete();

        return redirect('admin/admissionsCommitteePage')->with('flash_message', 'Блок удален');
    }
}
