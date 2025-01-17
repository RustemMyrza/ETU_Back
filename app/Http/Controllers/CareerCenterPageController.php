<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerCenterPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class CareerCenterPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $careerCenterPage = CareerCenterPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $careerCenterPage = CareerCenterPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('careerCenterPage.index', compact('careerCenterPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('careerCenterPage.create');
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


        $careerCenterPage= new CareerCenterPage();
        $careerCenterPage->title = $titleId;
        $careerCenterPage->content = $contentId;
        $careerCenterPage->image = $path ?? null;
        $careerCenterPage->save();

        return redirect('admin/careerCenterPage')->with('flash_message', 'Блок добавлен');
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
        $careerCenterPage = CareerCenterPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($careerCenterPage->title);
        $translatedContent = Translate::findOrFail($careerCenterPage->content);
        $image = Translate::findOrFail($careerCenterPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('careerCenterPage.show', compact('careerCenterPage', 'translatedData'));
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
        $careerCenterPage = CareerCenterPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($careerCenterPage->title);
        $translatedContent = Translate::findOrFail($careerCenterPage->content);
        $image = Translate::findOrFail($careerCenterPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('careerCenterPage.edit', compact('careerCenterPage', 'translatedData'));
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
        $careerCenterPage = CareerCenterPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($careerCenterPage->image != null) {
                unlink($careerCenterPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $careerCenterPage->image = $path;
        }

        $content = Translate::find($careerCenterPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($careerCenterPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $careerCenterPage->update();

        return redirect('admin/careerCenterPage')->with('flash_message', 'Блок изменен');
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
        $careerCenterPage = CareerCenterPage::find($id);
        if ($careerCenterPage->image != null) {
            unlink($careerCenterPage->image);
        }
        $content = Translate::find($careerCenterPage->content);
        $title = Translate::find($careerCenterPage->title);
        $title->delete();
        $content->delete();
        $careerCenterPage->delete();

        return redirect('admin/careerCenterPage')->with('flash_message', 'Блок удален');
    }
}
