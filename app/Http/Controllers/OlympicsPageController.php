<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OlympicsPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class OlympicsPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $olympicsPage = OlympicsPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $olympicsPage = OlympicsPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('olympicsPage.index', compact('olympicsPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('olympicsPage.create');
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


        $olympicsPage= new OlympicsPage();
        $olympicsPage->title = $titleId;
        $olympicsPage->content = $contentId;
        $olympicsPage->image = $path ?? null;
        $olympicsPage->save();

        return redirect('admin/olympicsPage')->with('flash_message', 'Блок добавлен');
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
        $olympicsPage = OlympicsPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($olympicsPage->title);
        $translatedContent = Translate::findOrFail($olympicsPage->content);
        $image = Translate::findOrFail($olympicsPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('olympicsPage.show', compact('olympicsPage', 'translatedData'));
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
        $olympicsPage = OlympicsPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($olympicsPage->title);
        $translatedContent = Translate::findOrFail($olympicsPage->content);
        $image = Translate::findOrFail($olympicsPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('olympicsPage.edit', compact('olympicsPage', 'translatedData'));
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
        $olympicsPage = OlympicsPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($olympicsPage->image != null) {
                Storage::disk('static')->delete($olympicsPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $olympicsPage->image = $path;
        }

        $content = Translate::find($olympicsPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($olympicsPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $olympicsPage->update();

        return redirect('admin/olympicsPage')->with('flash_message', 'Блок изменен');
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
        $olympicsPage = OlympicsPage::find($id);
        if ($olympicsPage->image != null) {
            Storage::disk('static')->delete($olympicsPage->image);
        }
        $content = Translate::find($olympicsPage->content);
        $content->delete();
        $olympicsPage->delete();

        return redirect('admin/olympicsPage')->with('flash_message', 'Блок удален');
    }
}
