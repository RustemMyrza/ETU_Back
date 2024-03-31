<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacancy;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $vacancy = Vacancy::where('position', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $vacancy = Vacancy::latest()->paginate($perPage);
        }
        return view('vacancy.index', compact('vacancy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vacancy.create');
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

        $vacancy= new Vacancy();
        $vacancy->position = $requestData['position'];
        $vacancy->description = $requestData['description'];
        $vacancy->experience = $requestData['experience'];
        $vacancy->date = $requestData['date'];
        $vacancy->save();

        return redirect('admin/vacancy')->with('flash_message', 'Блок добавлен');
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
        $vacancy = Vacancy::findOrFail($id);
        return view('vacancy.show', compact('vacancy'));
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
        $vacancy = Vacancy::findOrFail($id);
        return view('vacancy.edit', compact('vacancy'));
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
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->position = $requestData['position'];
        $vacancy->description = $requestData['description'];
        $vacancy->experience = $requestData['experience'];
        $vacancy->date = $requestData['date'];
        $vacancy->update();

        return redirect('admin/vacancy')->with('flash_message', 'Блок изменен');
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
        $vacancy = Vacancy::find($id);
        $vacancy->delete();

        return redirect('admin/vacancy')->with('flash_message', 'Блок удален');
    }
}
