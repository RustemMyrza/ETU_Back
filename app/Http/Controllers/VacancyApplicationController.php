<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacancyApplication;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Storage;

class VacancyApplicationController extends Controller
{
    public function index(Request $request, $vacancyId)
    {
        $vacancyPosition = Vacancy::findOrFail($vacancyId)->position;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $vacancyApplication = VacancyApplication::where('name', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $vacancyApplication = VacancyApplication::where('parent_id', $vacancyId)
                ->latest()
                ->paginate($perPage);
        }
        // $this->getDataFromTable();
        return view('vacancyApplication.index', compact('vacancyApplication', 'vacancyPosition', 'vacancyId'));
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
        $request->validate([
            'document' => 'document|mimes:pdf,docx|max:2048',
        ],
            [
                'document.mimes' => 'Проверьте формат документа',
                'document.max' => 'Размер файла не может превышать 2МБ'
            ]);
        $requestData = $request->all();
        if ($request->hasFile('summary')) {
            $summaryPath = $this->uploadDocument($request->file('summary'));
        }
        if ($request->hasFile('letter')) {
            $letterPath = $this->uploadDocument($request->file('letter'));
        }
        if ($request->hasFile('education')) {
            $educationPath = $this->uploadDocument($request->file('education'));
        }
        if ($request->hasFile('recommender')) {
            $recommenderPath = $this->uploadDocument($request->file('recommender'));
        }

        $vacancyApplication= new VacancyApplication();
        $vacancyApplication->name = $requestData['name'];
        $vacancyApplication->phone = $requestData['phone'];
        $vacancyApplication->email = $requestData['email'];
        $vacancyApplication->summary = $summaryPath ?? null;
        $vacancyApplication->letter = $letterPath ?? null;
        $vacancyApplication->education = $educationPath ?? null;
        $vacancyApplication->recommender = $recommenderPath ?? null;
        $vacancyApplication->parent_id = $vacancyId;
        $vacancyApplication->save();

        return 'Заявка была отправлена';
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($vacancyId, $id)
    {
        $vacancyApplication = VacancyApplication::findOrFail($id);
        return view('vacancyApplication.show', compact('vacancyApplication', 'vacancyId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */

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
    public function destroy($vacancyId, $id)
    {
        $vacancyApplication = VacancyApplication::find($id);
        if ($vacancyApplication->summary != null) {
            unlink($vacancyApplication->summary);
        }
        if ($vacancyApplication->letter != null) {
            unlink($vacancyApplication->letter);
        }
        if ($vacancyApplication->education != null) {
            unlink($vacancyApplication->education);
        }
        if ($vacancyApplication->recommender != null) {
            unlink($vacancyApplication->recommender);
        }
        $vacancyApplication->delete();

        return redirect('admin/vacancy/' . $vacancyId . '/applications')->with('flash_message', 'Блок удален');
    }
}
