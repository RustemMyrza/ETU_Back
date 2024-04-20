<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MainPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $translatedDataId = [];
            // $mainPage = [];
            // $allData = MainPage::latest()->paginate($perPage);
            // foreach($allData as $item)
            // {
            //     if ($item->getContent->ru == $keyword || $item->getTitle->ru == $keyword)
            //     {
            //         $mainPage[] = $item;
            //     }
            // }
            $mainPage = Translate::where('ru', 'LIKE', "%$keyword%");
            foreach ($mainPage as $item)
            {
                $translatedDataId[] = $item->id;
            }
            foreach ($translatedDataId as $item)
            {
                $mainPage = MainPage::where('title', 'LIKE', $item)
                    ->orWhere('content', 'LIKE', $item)
                    ->latest()->paginate($perPage);
            }
            return $mainPage;
        } else {
            $mainPage = MainPage::latest()->paginate($perPage);
        }
        // $this->getDataFromTable();
        $translatesData = Translate::all();
        return view('mainPage.index', compact('mainPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('mainPage.create');
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


        $mainPage= new MainPage();
        $mainPage->title = $titleId;
        $mainPage->content = $contentId;
        $mainPage->image = $path ?? null;
        $mainPage->save();

        return redirect('admin/mainPage')->with('flash_message', 'Блок добавлен');
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
        $mainPage = MainPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($mainPage->title);
        $translatedContent = Translate::findOrFail($mainPage->content);
        $image = Translate::findOrFail($mainPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('mainPage.show', compact('mainPage', 'translatedData'));
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
        $mainPage = MainPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($mainPage->title);
        $translatedContent = Translate::findOrFail($mainPage->content);
        $image = Translate::findOrFail($mainPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('mainPage.edit', compact('mainPage', 'translatedData'));
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
        $mainPage = MainPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($mainPage->image != null) {
                unlink($mainPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $mainPage->image = $path;
        }

        $content = Translate::find($mainPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($mainPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $mainPage->update();

        return redirect('admin/mainPage')->with('flash_message', 'Блок изменен');
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
        $mainPage = MainPage::find($id);
        if ($mainPage->image != null) {
            unlink($mainPage->image);
        }
        $content = Translate::find($mainPage->content);
        $content->delete();
        $title = Translate::find($mainPage->title);
        $title->delete();
        $mainPage->delete();

        return redirect('admin/mainPage')->with('flash_message', 'Блок удален');
    }
}
