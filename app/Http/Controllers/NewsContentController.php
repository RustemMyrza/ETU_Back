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
            $newsContent = NewsContent::latest()->paginate($perPage);
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
    public function create()
    {
        return view('newsContent.create');
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
        dd($newsId);
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

        $header = new Translate();
        $header->ru = $requestData['header']['ru'];
        $header->en = $requestData['header']['en'];
        $header->kz = $requestData['header']['kz'];
        $header->save();
        $headerId = $header->id;


        $newsContent= new NewsContent();
        $newsContent->header = $headerId;
        $newsContent->content = $contentId;
        $newsContent->image = $path ?? null;
        $newsContent->save();

        return redirect('admin/newsContent')->with('flash_message', 'Блок добавлен');
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
        $newsContent = NewsContent::findOrFail($id);
        $translatedHeader = Translate::findOrFail($newsContent->header);
        $translatedContent = Translate::findOrFail($newsContent->content);
        $image = Translate::findOrFail($newsContent->content);
        $translatedData['header'] = $translatedHeader;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('newsContent.show', compact('newsContent', 'translatedData'));
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
        $newsContent = NewsContent::findOrFail($id);
        $translatedHeader = Translate::findOrFail($newsContent->header);
        $translatedContent = Translate::findOrFail($newsContent->content);
        $image = Translate::findOrFail($newsContent->content);
        $translatedData['header'] = $translatedHeader;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('newsContent.edit', compact('newsContent', 'translatedData'));
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

        $header = Translate::find($newsContent->header);
        $header->ru = $requestData['header']['ru'];
        $header->en = $requestData['header']['en'];
        $header->kz = $requestData['header']['kz'];
        $header->update();

        $newsContent->update();

        return redirect('admin/newsContent')->with('flash_message', 'Блок изменен');
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
        $newsContent = NewsContent::find($id);
        if ($newsContent->image != null) {
            Storage::disk('static')->delete($newsContent->image);
        }
        $content = Translate::find($newsContent->content);
        $content->delete();
        $newsContent->delete();

        return redirect('admin/newsContent')->with('flash_message', 'Блок удален');
    }
}
