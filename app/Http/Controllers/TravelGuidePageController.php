<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TravelGuidePage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class TravelGuidePageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $travelGuidePage = TravelGuidePage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $travelGuidePage = TravelGuidePage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('travelGuidePage.index', compact('travelGuidePage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('travelGuidePage.create');
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


        $travelGuidePage= new TravelGuidePage();
        $travelGuidePage->title = $titleId;
        $travelGuidePage->content = $contentId;
        $travelGuidePage->image = $path ?? null;
        $travelGuidePage->save();

        return redirect('admin/travelGuidePage')->with('flash_message', 'Блок добавлен');
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
        $travelGuidePage = TravelGuidePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($travelGuidePage->title);
        $translatedContent = Translate::findOrFail($travelGuidePage->content);
        $image = Translate::findOrFail($travelGuidePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('travelGuidePage.show', compact('travelGuidePage', 'translatedData'));
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
        $travelGuidePage = TravelGuidePage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($travelGuidePage->title);
        $translatedContent = Translate::findOrFail($travelGuidePage->content);
        $image = Translate::findOrFail($travelGuidePage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('travelGuidePage.edit', compact('travelGuidePage', 'translatedData'));
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
        $travelGuidePage = TravelGuidePage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($travelGuidePage->image != null) {
                unlink($travelGuidePage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $travelGuidePage->image = $path;
        }

        $content = Translate::find($travelGuidePage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($travelGuidePage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $travelGuidePage->update();

        return redirect('admin/travelGuidePage')->with('flash_message', 'Блок изменен');
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
        $travelGuidePage = TravelGuidePage::find($id);
        if ($travelGuidePage->image != null) {
            unlink($travelGuidePage->image);
        }
        $content = Translate::find($travelGuidePage->content);
        $content->delete();
        $travelGuidePage->delete();

        return redirect('admin/travelGuidePage')->with('flash_message', 'Блок удален');
    }
}
