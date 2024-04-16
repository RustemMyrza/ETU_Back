<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsContent;
use App\Models\News;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class NewsContentController extends Controller
{
    public function index(Request $request, $newsId)
    {
        // dd($newsId);
        $newsName = News::findOrFail($newsId)->with('getName')->get()[0]->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $newsContent = NewsContent::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $newsContent = NewsContent::where('parent_id', $newsId)
                ->latest()
                ->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('newsContent.index', compact('newsContent', 'translatesData', 'newsId', 'newsName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($newsId)
    {
        // dd($newsId);
        return view('newsContent.create', compact('newsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $newsId)
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


        $newsContent= new NewsContent();
        $newsContent->title = $titleId;
        $newsContent->content = $contentId;
        $newsContent->image = $path ?? null;
        $newsContent->parent_id = $newsId;
        $newsContent->save();

        return redirect('admin/news/' . $newsId . '/content')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($newsId, $id)
    {
        $newsContent = NewsContent::findOrFail($id);
        $translatedTitle = Translate::findOrFail($newsContent->title);
        $translatedContent = Translate::findOrFail($newsContent->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $newsContent->image;
        return view('newsContent.show', compact('newsContent', 'translatedData', 'newsId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($newsId, $id)
    {
        $newsContent = NewsContent::findOrFail($id);
        $translatedTitle = Translate::findOrFail($newsContent->title);
        $translatedContent = Translate::findOrFail($newsContent->content);
        $image = Translate::findOrFail($newsContent->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('newsContent.edit', compact('newsContent', 'translatedData', 'newsId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id, $newsId)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
            [
                'image.mimes' => 'Проверьте формат изображения',
                'image.max' => 'Размер файла не может превышать 2МБ'
            ]);

        $requestData = $request->all();
        $newsContent = NewsContent::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($newsContent->image != null) {
                Storage::disk('static')->delete($newsContent->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $newsContent->image = $path;
        }

        $content = Translate::find($newsContent->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($newsContent->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $newsContent->update();

        return redirect('admin/news/' . $newsId . '/content')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($newsId, $id)
    {
        $newsContent = NewsContent::find($id);
        if ($newsContent->image != null) {
            unlink($newsContent->image);
        }

        $content = Translate::find($newsContent->content);
        $title = Translate::find($newsContent->title);
        $title->delete();
        $content->delete();
        $newsContent->delete();

        return redirect('admin/news/' . $newsId . '/content')->with('flash_message', 'Блок удален');
    }
}
