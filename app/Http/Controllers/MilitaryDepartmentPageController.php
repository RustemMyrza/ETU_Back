<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MilitaryDepartmentPage;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class MilitaryDepartmentPageController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $militaryDepartmentPage = MilitaryDepartmentPage::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $militaryDepartmentPage = MilitaryDepartmentPage::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('militaryDepartmentPage.index', compact('militaryDepartmentPage', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('militaryDepartmentPage.create');
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


        $militaryDepartmentPage= new MilitaryDepartmentPage();
        $militaryDepartmentPage->title = $titleId;
        $militaryDepartmentPage->content = $contentId;
        $militaryDepartmentPage->image = $path ?? null;
        $militaryDepartmentPage->save();

        return redirect('admin/militaryDepartmentPage')->with('flash_message', 'Блок добавлен');
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
        $militaryDepartmentPage = MilitaryDepartmentPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($militaryDepartmentPage->title);
        $translatedContent = Translate::findOrFail($militaryDepartmentPage->content);
        $image = Translate::findOrFail($militaryDepartmentPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('militaryDepartmentPage.show', compact('militaryDepartmentPage', 'translatedData'));
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
        $militaryDepartmentPage = MilitaryDepartmentPage::findOrFail($id);
        $translatedTitle = Translate::findOrFail($militaryDepartmentPage->title);
        $translatedContent = Translate::findOrFail($militaryDepartmentPage->content);
        $image = Translate::findOrFail($militaryDepartmentPage->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('militaryDepartmentPage.edit', compact('militaryDepartmentPage', 'translatedData'));
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
        $militaryDepartmentPage = MilitaryDepartmentPage::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($militaryDepartmentPage->image != null) {
                Storage::disk('static')->delete($militaryDepartmentPage->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $militaryDepartmentPage->image = $path;
        }

        $content = Translate::find($militaryDepartmentPage->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($militaryDepartmentPage->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $militaryDepartmentPage->update();

        return redirect('admin/militaryDepartmentPage')->with('flash_message', 'Блок изменен');
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
        $militaryDepartmentPage = MilitaryDepartmentPage::find($id);
        if ($militaryDepartmentPage->image != null) {
            Storage::disk('static')->delete($militaryDepartmentPage->image);
        }
        $content = Translate::find($militaryDepartmentPage->content);
        $content->delete();
        $militaryDepartmentPage->delete();

        return redirect('admin/militaryDepartmentPage')->with('flash_message', 'Блок удален');
    }
}