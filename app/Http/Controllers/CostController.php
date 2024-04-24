<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cost;
use App\Models\Translate;

class CostController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $cost = Cost::where('program', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $cost = Cost::latest()->paginate($perPage);
            $formEducation = [
                1 => 'Очное отделение Бакалавриат',
                2 => 'Сокращенное отделение Бакалавриат',
                3 => 'Магистратура'
            ];
            $translatesData = Translate::all();
        }

        return view('cost.index', compact('cost', 'translatesData', 'formEducation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cost.create');
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
        // dd($requestData);
        $program = new Translate();
        $program->ru = $requestData['program']['ru'];
        $program->en = $requestData['program']['en'];
        $program->kz = $requestData['program']['kz'];
        $program->save();
        $programId = $program->id;

        $cost= new Cost();
        $cost->program = $programId;
        $cost->first = $requestData['first_year'];
        $cost->second = $requestData['second_year'];
        $cost->third = $requestData['third_year'];
        $cost->fourth = $requestData['fourth_year'];
        $cost->fifth = $requestData['fifth_year'];
        $cost->total = $requestData['total'];
        $cost->type = $requestData['type'];
        $cost->save();

        return redirect('admin/cost')->with('flash_message', 'Блок добавлен');
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
        $cost = Cost::findOrFail($id);
        $translatedProgram = Translate::findOrFail($cost->program);
        $formEducation = [
            1 => 'На очное отделение',
            2 => 'На сокращенную форму',
            3 => 'Магистратура'
        ];
        return view('cost.show', compact('cost', 'translatedProgram', 'formEducation'));
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
        $cost = Cost::findOrFail($id);
        $translatedProgram = Translate::findOrFail($cost->program);
        return view('cost.edit', compact('cost', 'translatedProgram'));
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
        $cost = Cost::findOrFail($id);

        $program = Translate::find($cost->program);
        $program->ru = $requestData['program']['ru'];
        $program->en = $requestData['program']['en'];
        $program->kz = $requestData['program']['kz'];
        $program->update();

        $cost->first = $requestData['first_year'];
        $cost->second = $requestData['second_year'];
        $cost->third = $requestData['third_year'];
        $cost->fourth = $requestData['fourth_year'];
        $cost->fifth = $requestData['fifth_year'];
        $cost->total = $requestData['total'];
        $cost->type = $requestData['type'];
        $cost->update();

        return redirect('admin/cost')->with('flash_message', 'Блок изменен');
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
        $cost = Cost::find($id);
        $program = Translate::find($cost->program);
        $program->delete();
        $cost->delete();

        return redirect('admin/cost')->with('flash_message', 'Блок удален');
    }
}
