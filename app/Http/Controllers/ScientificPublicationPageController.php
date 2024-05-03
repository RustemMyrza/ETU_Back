<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScientificPublicationPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class ScientificPublicationPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $scientificPublicationPage = ScientificPublicationPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $scientificPublicationPage = ScientificPublicationPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('scientificPublicationPage.index', compact('scientificPublicationPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('scientificPublicationPage.create');
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


        $scientificPublicationPage= new ScientificPublicationPage();
        $scientificPublicationPage->title = $titleId;
        $scientificPublicationPage->content = $contentId;
        $scientificPublicationPage->image = $path ?? null;
        $scientificPublicationPage->save();

        return redirect('admin/scientificPublicationPage')->with('flash_message', 'Блок добавлен');
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
        $scientificPublicationPage = ScientificPublicationPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($scientificPublicationPage->title);
        $translatedContent = Translate::findOrFail($scientificPublicationPage->content);
        $image = Translate::findOrFail($scientificPublicationPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('scientificPublicationPage.show', compact('scientificPublicationPage', 'translatedData'));
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
        $scientificPublicationPage = ScientificPublicationPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($scientificPublicationPage->title);
        $translatedContent = Translate::findOrFail($scientificPublicationPage->content);
        $image = Translate::findOrFail($scientificPublicationPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('scientificPublicationPage.edit', compact('scientificPublicationPage', 'translatedData'));
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
        $scientificPublicationPage = ScientificPublicationPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($scientificPublicationPage->image != null) {
                unlink($scientificPublicationPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $scientificPublicationPage->image = $path;
        }

        $content = Translate::find($scientificPublicationPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($scientificPublicationPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $scientificPublicationPage->update();

        return redirect('admin/scientificPublicationPage')->with('flash_message', 'Блок изменен');
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
        $scientificPublicationPage = ScientificPublicationPage::find($id);
        if ($scientificPublicationPage->image != null) {
            unlink($scientificPublicationPage->image);
        }
        $content = Translate::find($scientificPublicationPage->content);
        $title = Translate::find($scientificPublicationPage->title);
        $title->delete();
        $content->delete();
        $scientificPublicationPage->delete();

        return redirect('admin/scientificPublicationPage')->with('flash_message', 'Блок удален');
    }
}
