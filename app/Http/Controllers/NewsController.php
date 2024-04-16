<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsContent;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $news = News::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $news = News::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('news.index', compact('news', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('news.create');
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
        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;

        $news= new News();
        $news->date = $requestData['date'];
        $news->name = $nameId;
        $news->background_image = $path ?? null;
        $news->save();

        return redirect('admin/news')->with('flash_message', 'Блок добавлен');
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
        $news = News::findOrFail($id);
        $translatedName = Translate::findOrFail($news->name);
        $newsData['id'] = $id;
        $newsData['name'] = $translatedName->ru;
        $newsData['image'] = $news->background_image;
        $newsData['date'] = $news->date;
        return view('news.show', compact('newsData'));
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
        $news = News::findOrFail($id);
        $translatedName = Translate::findOrFail($news->name);
        $date = $news->date;
        $image = $news->background_image;
        $newsData['id'] = $id;
        $newsData['name'] =  $translatedName;
        $newsData['date'] = $date;
        $newsData['image'] = $image;
        return view('news.edit', compact('newsData'));
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
        $news = News::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($news->background_image != null) {
                Storage::disk('static')->delete($news->background_image);
            }
            $path = $this->uploadImage($request->file('image'));
            $news->background_image = $path;
        }

        $name = Translate::findOrFail($news->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();
        $news->update();

        return redirect('admin/news')->with('flash_message', 'Блок изменен');
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
        $news = News::findOrFail($id);
        if ($news->background_image != null) {
            unlink($news->background_image);
        }
        $name = Translate::findOrFail($news->name);
        $newsContent = NewsContent::where('parent_id', $id)->get();
        if (count($newsContent) > 0)
        {
            foreach($newsContent as $item)
            {
                if ($item->title)
                {
                    $title = Translate::findOrFail($item->title);
                    $title->delete();
                }
                if ($item->content)
                {
                    $content = Translate::findOrFail($item->content);
                    $content->delete();
                }
                $item->delete();
            }
        }
        $name->delete();
        $news->delete();

        return redirect('admin/news')->with('flash_message', 'Блок удален');
    }
}
