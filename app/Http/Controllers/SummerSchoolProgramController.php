<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummerSchoolProgram;
use App\Models\Translate;

class SummerSchoolProgramController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $summerSchoolProgram = SummerSchoolProgram::where('title', 'LIKE', "%$keyword%")
                ->orWhere('text', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $summerSchoolProgram = SummerSchoolProgram::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('summerSchoolProgram.index', compact('summerSchoolProgram', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('summerSchoolProgram.create');
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

        $title = new Translate();
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->save();
        $titleId = $title->id;

        $text = new Translate();
        $text->ru = $requestData['text']['ru'];
        $text->en = $requestData['text']['en'];
        $text->kz = $requestData['text']['kz'];
        $text->save();
        $textId = $text->id;


        $summerSchoolProgram= new SummerSchoolProgram();
        $summerSchoolProgram->title = $titleId;
        $summerSchoolProgram->text = $textId;
        $summerSchoolProgram->save();

        return redirect('admin/summerSchoolProgram')->with('flash_message', 'Блок добавлен');
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
        $summerSchoolProgram = SummerSchoolProgram::findOrFail($id);
        $translatedTitle = Translate::findOrFail($summerSchoolProgram->title);
        $translatedText = Translate::findOrFail($summerSchoolProgram->text);
        $translatedData['title'] = $translatedTitle;
        $translatedData['text'] = $translatedText;
        return view('summerSchoolProgram.show', compact('id', 'translatedData'));
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
        $summerSchoolProgram = SummerSchoolProgram::findOrFail($id);
        $translatedTitle = Translate::findOrFail($summerSchoolProgram->title);
        $translatedText = Translate::findOrFail($summerSchoolProgram->text);
        $translatedData['title'] = $translatedTitle;
        $translatedData['text'] = $translatedText;
        return view('summerSchoolProgram.edit', compact('id', 'translatedData'));
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
        $summerSchoolProgram = SummerSchoolProgram::findOrFail($id);

        $title = Translate::find($summerSchoolProgram->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $text = Translate::find($summerSchoolProgram->text);
        $text->ru = $requestData['text']['ru'];
        $text->en = $requestData['text']['en'];
        $text->kz = $requestData['text']['kz'];
        $text->update();

        $summerSchoolProgram->update();

        return redirect('admin/summerSchoolProgram')->with('flash_message', 'Блок изменен');
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
        $summerSchoolProgram = SummerSchoolProgram::find($id);
        $title = Translate::find($summerSchoolProgram->title);
        $text = Translate::find($summerSchoolProgram->text);
        $title->delete();
        $text->delete();
        $summerSchoolProgram->delete();

        return redirect('admin/summerSchoolProgram')->with('flash_message', 'Блок удален');
    }
}
