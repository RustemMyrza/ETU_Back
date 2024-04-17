<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentScience;
use App\Models\Translate;
use Illuminate\Support\Facades\Storage;

class StudentScienceController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $studentScience = StudentScience::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $studentScience = StudentScience::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('studentScience.index', compact('studentScience', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('studentScience.create');
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


        $studentScience= new StudentScience();
        $studentScience->title = $titleId;
        $studentScience->content = $contentId;
        $studentScience->image = $path ?? null;
        $studentScience->save();

        return redirect('admin/studentScience')->with('flash_message', 'Блок добавлен');
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
        $studentScience = StudentScience::findOrFail($id);
        $translatedTitle = Translate::findOrFail($studentScience->title);
        $translatedContent = Translate::findOrFail($studentScience->content);
        $image = Translate::findOrFail($studentScience->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('studentScience.show', compact('studentScience', 'translatedData'));
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
        $studentScience = StudentScience::findOrFail($id);
        $translatedTitle = Translate::findOrFail($studentScience->title);
        $translatedContent = Translate::findOrFail($studentScience->content);
        $image = Translate::findOrFail($studentScience->content);
        $translatedData['title'] = $translatedTitle;
        $translatedData['content'] = $translatedContent;
        $translatedData['image'] = $image;
        return view('studentScience.edit', compact('studentScience', 'translatedData'));
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
        $studentScience = StudentScience::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($studentScience->image != null) {
                unlink($studentScience->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $studentScience->image = $path;
        }

        $content = Translate::find($studentScience->content);
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->update();

        $title = Translate::find($studentScience->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $studentScience->update();

        return redirect('admin/studentScience')->with('flash_message', 'Блок изменен');
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
        $studentScience = StudentScience::find($id);
        if ($studentScience->image != null) {
            unlink($studentScience->image);
        }
        $content = Translate::find($studentScience->content);
        $title = Translate::find($studentScience->title);
        $title->delete();
        $content->delete();
        $studentScience->delete();

        return redirect('admin/studentScience')->with('flash_message', 'Блок удален');
    }
}
