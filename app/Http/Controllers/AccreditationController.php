<?php

namespace App\Http\Controllers;

use App\Models\Accreditation;
use App\Models\Translate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccreditationController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $accreditation = Accreditation::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $accreditation = Accreditation::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('accreditation.index', compact('accreditation', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('accreditation.create');
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


        $accreditation= new Accreditation();
        $accreditation->title = $titleId;
        $accreditation->content = $contentId;
        $accreditation->image = $path ?? null;
        $accreditation->save();

        return redirect('admin/accreditation')->with('flash_message', 'Блок добавлен');
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
        $accreditation = Accreditation::findOrFail($id);
        $translatedTitle = Translate::findOrFail($accreditation->title);
        $translatedContent = Translate::findOrFail($accreditation->content);
        $image = Translate::findOrFail($accreditation->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('accreditation.show', compact('accreditation', 'translatedData'));
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
        $accreditation = Accreditation::findOrFail($id);
        $translatedTitle = Translate::findOrFail($accreditation->title);
        $translatedContent = Translate::findOrFail($accreditation->content);
        $image = Translate::findOrFail($accreditation->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('accreditation.edit', compact('accreditation', 'translatedData'));
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
        $accreditation = Accreditation::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($accreditation->image != null) {
                Storage::disk('static')->delete($accreditation->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $accreditation->image = $path;
        }

        $content = Translate::find($accreditation->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($accreditation->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $accreditation->update();

        return redirect('admin/accreditation')->with('flash_message', 'Блок изменен');
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
        $accreditation = Accreditation::find($id);
        if ($accreditation->image != null) {
            unlink($accreditation->image);
        }
        $content = Translate::find($accreditation->content);
        $content->delete();
        $accreditation->delete();

        return redirect('admin/accreditation')->with('flash_message', 'Блок удален');
    }
}
