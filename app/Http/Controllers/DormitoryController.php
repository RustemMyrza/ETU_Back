<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dormitory;
use App\Models\Translate;

class DormitoryController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $dormitory = Dormitory::where('content', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $dormitory = Dormitory::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }

        return view('dormitory.index', compact('dormitory', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dormitory.create');
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
        $requestData = $request->all();
        $content = new Translate();
        $content->ru = $requestData['content']['ru'];
        $content->en = $requestData['content']['en'];
        $content->kz = $requestData['content']['kz'];
        $content->save();
        $contentId = $content->id;

        $dormitory= new Dormitory();
        $dormitory->content = $contentId;
        $dormitory->dormitory_id = $requestData['dormitory'];
        $dormitory->save();

        return redirect('admin/dormitory')->with('flash_message', 'Блок добавлен');
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
        $dormitory = Dormitory::findOrFail($id);
        $translatedContent = Translate::findOrFail($dormitory->content);
        return view('dormitory.show', compact('dormitory', 'translatedContent'));
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
        $dormitory = Dormitory::findOrFail($id);
        $translatedContent = Translate::findOrFail($dormitory->content);
        return view('dormitory.edit', compact('dormitory', 'translatedContent'));
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
        $requestData = $request->all();
        $dormitory = Dormitory::findOrFail($id);

        $category = Translate::find($dormitory->category);
        $category->ru = $requestData['content']['ru'];
        $category->en = $requestData['content']['en'];
        $category->kz = $requestData['content']['kz'];
        $category->update();

        $dormitory->dormitory_id = $requestData['dormitory'];
        $dormitory->update();

        return redirect('admin/dormitory')->with('flash_message', 'Блок изменен');
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
        $dormitory = Dormitory::find($id);
        $content = Translate::find($dormitory->content);
        $content->delete();
        $dormitory->delete();

        return redirect('admin/dormitory')->with('flash_message', 'Блок удален');
    }
}
