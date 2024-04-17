<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScienceInnovationPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class ScienceInnovationPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $scienceInnovationPage = ScienceInnovationPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $scienceInnovationPage = ScienceInnovationPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('scienceInnovationPage.index', compact('scienceInnovationPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('scienceInnovationPage.create');
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


        $scienceInnovationPage= new ScienceInnovationPage();
        $scienceInnovationPage->title = $titleId;
        $scienceInnovationPage->content = $contentId;
        $scienceInnovationPage->image = $path ?? null;
        $scienceInnovationPage->save();

        return redirect('admin/scienceInnovationPage')->with('flash_message', 'Блок добавлен');
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
        $scienceInnovationPage = ScienceInnovationPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($scienceInnovationPage->title);
        $translatedContent = Translate::findOrFail($scienceInnovationPage->content);
        $image = Translate::findOrFail($scienceInnovationPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('scienceInnovationPage.show', compact('scienceInnovationPage', 'translatedData'));
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
        $scienceInnovationPage = ScienceInnovationPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($scienceInnovationPage->title);
        $translatedContent = Translate::findOrFail($scienceInnovationPage->content);
        $image = Translate::findOrFail($scienceInnovationPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('scienceInnovationPage.edit', compact('scienceInnovationPage', 'translatedData'));
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
        $scienceInnovationPage = ScienceInnovationPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($scienceInnovationPage->image != null) {
                unlink($scienceInnovationPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $scienceInnovationPage->image = $path;
        }

        $content = Translate::find($scienceInnovationPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($scienceInnovationPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $scienceInnovationPage->update();

        return redirect('admin/scienceInnovationPage')->with('flash_message', 'Блок изменен');
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
        $scienceInnovationPage = ScienceInnovationPage::find($id);
        if ($scienceInnovationPage->image != null) {
            unlink($scienceInnovationPage->image);
        }
        $content = Translate::find($scienceInnovationPage->content);
        $content->delete();
        $scienceInnovationPage->delete();

        return redirect('admin/scienceInnovationPage')->with('flash_message', 'Блок удален');
    }
}
