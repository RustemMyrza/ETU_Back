<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummerSchoolPage;
use App\Models\Translate;

class SummerSchoolPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $summerSchoolPage = SummerSchoolPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $summerSchoolPage = SummerSchoolPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('summerSchoolPage.index', compact('summerSchoolPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('summerSchoolPage.create');
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


        $summerSchoolPage= new SummerSchoolPage();
        $summerSchoolPage->title = $titleId;
        $summerSchoolPage->content = $contentId;
        $summerSchoolPage->image = $path ?? null;
        $summerSchoolPage->save();

        return redirect('admin/summerSchoolPage')->with('flash_message', 'Блок добавлен');
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
        $summerSchoolPage = SummerSchoolPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($summerSchoolPage->title);
        $translatedContent = Translate::findOrFail($summerSchoolPage->content);
        $image = Translate::findOrFail($summerSchoolPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('summerSchoolPage.show', compact('summerSchoolPage', 'translatedData'));
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
        $summerSchoolPage = SummerSchoolPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($summerSchoolPage->title);
        $translatedContent = Translate::findOrFail($summerSchoolPage->content);
        $image = Translate::findOrFail($summerSchoolPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('summerSchoolPage.edit', compact('summerSchoolPage', 'translatedData'));
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
        $summerSchoolPage = SummerSchoolPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($summerSchoolPage->image != null) {
                unlink($summerSchoolPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $summerSchoolPage->image = $path;
        }

        $content = Translate::find($summerSchoolPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($summerSchoolPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $summerSchoolPage->update();

        return redirect('admin/summerSchoolPage')->with('flash_message', 'Блок изменен');
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
        $summerSchoolPage = SummerSchoolPage::find($id);
        if ($summerSchoolPage->image != null) {
            unlink($summerSchoolPage->image);
        }
        $title = Translate::find($summerSchoolPage->title);
        $content = Translate::find($summerSchoolPage->content);
        $content->delete();
        $title->delete();
        $summerSchoolPage->delete();

        return redirect('admin/summerSchoolPage')->with('flash_message', 'Блок удален');
    }
}
