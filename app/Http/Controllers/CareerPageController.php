<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class CareerPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $careerPage = CareerPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $careerPage = CareerPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('careerPage.index', compact('careerPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('careerPage.create');
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


        $careerPage= new CareerPage();
        $careerPage->title = $titleId;
        $careerPage->content = $contentId;
        $careerPage->image = $path ?? null;
        $careerPage->save();

        return redirect('admin/careerPage')->with('flash_message', 'Блок добавлен');
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
        $careerPage = CareerPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($careerPage->title);
        $translatedContent = Translate::findOrFail($careerPage->content);
        $image = Translate::findOrFail($careerPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('careerPage.show', compact('careerPage', 'translatedData'));
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
        $careerPage = CareerPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($careerPage->title);
        $translatedContent = Translate::findOrFail($careerPage->content);
        $image = Translate::findOrFail($careerPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('careerPage.edit', compact('careerPage', 'translatedData'));
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
        $careerPage = CareerPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($careerPage->image != null) {
                Storage::disk('static')->delete($careerPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $careerPage->image = $path;
        }

        $content = Translate::find($careerPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($careerPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $careerPage->update();

        return redirect('admin/careerPage')->with('flash_message', 'Блок изменен');
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
        $careerPage = CareerPage::find($id);
        if ($careerPage->image != null) {
            Storage::disk('static')->delete($careerPage->image);
        }
        $content = Translate::find($careerPage->content);
        $content->delete();
        $careerPage->delete();

        return redirect('admin/careerPage')->with('flash_message', 'Блок удален');
    }
}
