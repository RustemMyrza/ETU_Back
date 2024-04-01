<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RectorsBlogQuestion;

class RectorsBlogQuestionController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $question = RectorsBlogQuestion::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $question = RectorsBlogQuestion::latest()
                ->paginate($perPage);
        }
        // $this->getDataFromTable();
        return view('rectorsBlogQuestion.index', compact('question'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $vacancyId)
    {
        // dd($request->all());
        $requestData = $request->all();

        $vacancyApplication= new RectorsBlogQuestion();
        $vacancyApplication->name = $requestData['name'];
        $vacancyApplication->surname = $requestData['surname'];
        $vacancyApplication->phone = $requestData['phone'];
        $vacancyApplication->email = $requestData['email'];
        $vacancyApplication->question = $requestData['question'];
        $vacancyApplication->save();

        return redirect('admin/rectorsBlogQuestion')->with('flash_message', 'Блок добавлен');
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
        $question = RectorsBlogQuestion::findOrFail($id);
        return view('rectorsBlogQuestion.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */


     public function edit ($id)
    {
        $schoolsPages = RectorsBlogQuestion::findOrFail($id);
        return view('schoolsPages.edit', compact('schoolsPages'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $question = RectorsBlogQuestion::find($id);
        $question->delete();

        return redirect('admin/rectorsBlogQuestion')->with('flash_message', 'Блок удален');
    }
}
