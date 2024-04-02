<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HonorsStudentDiscount;
use App\Models\Translate;

class HonorsStudentDiscountController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $honorsStudentDiscount = HonorsStudentDiscount::where('category', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $honorsStudentDiscount = HonorsStudentDiscount::latest()->paginate($perPage);
            $forTypeStudents = [
                1 => 'Для абитуриентов',
                2 => 'Для поступающих в магистратуру'
            ];
            $translatesData = Translate::all();
        }

        return view('honorsStudentDiscount.index', compact('honorsStudentDiscount', 'translatesData', 'forTypeStudents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('honorsStudentDiscount.create');
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
        $category = new Translate();
        $category->ru = $requestData['category']['ru'];
        $category->en = $requestData['category']['en'];
        $category->kz = $requestData['category']['kz'];
        $category->save();
        $categoryId = $category->id;

        $note = new Translate();
        $note->ru = $requestData['note']['ru'];
        $note->en = $requestData['note']['en'];
        $note->kz = $requestData['note']['kz'];
        $note->save();
        $noteId = $note->id;


        $honorsStudentDiscount= new HonorsStudentDiscount();
        $honorsStudentDiscount->category = $categoryId;
        $honorsStudentDiscount->note = $noteId;
        $honorsStudentDiscount->amount = $requestData['amount'];
        $honorsStudentDiscount->gpa = $requestData['gpa'];
        $honorsStudentDiscount->save();

        return redirect('admin/honorsStudentDiscount')->with('flash_message', 'Блок добавлен');
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
        $honorsStudentDiscount = HonorsStudentDiscount::findOrFail($id);
        $translatedCategory = Translate::findOrFail($honorsStudentDiscount->category);
        $translatedNote = Translate::findOrFail($honorsStudentDiscount->note);
        $translatedData['category'] = $translatedCategory;
        $translatedData['note'] = $translatedNote;
        $forTypeStudents = [
            1 => 'Для абитуриентов',
            2 => 'Для поступающих в магистратуру'
        ];
        return view('honorsStudentDiscount.show', compact('honorsStudentDiscount', 'translatedData', 'forTypeStudents'));
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
        $honorsStudentDiscount = HonorsStudentDiscount::findOrFail($id);
        $translatedCategory = Translate::findOrFail($honorsStudentDiscount->category);
        $translatedNote = Translate::findOrFail($honorsStudentDiscount->note);
        $translatedData['category'] = $translatedCategory;
        $translatedData['note'] = $translatedNote;
        return view('honorsStudentDiscount.edit', compact('honorsStudentDiscount', 'translatedData'));
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
        $honorsStudentDiscount = HonorsStudentDiscount::findOrFail($id);

        $category = Translate::find($honorsStudentDiscount->category);
        $category->ru = $requestData['category']['ru'];
        $category->en = $requestData['category']['en'];
        $category->kz = $requestData['category']['kz'];
        $category->update();

        $note = Translate::find($honorsStudentDiscount->note);
        $note->ru = $requestData['note']['ru'];
        $note->en = $requestData['note']['en'];
        $note->kz = $requestData['note']['kz'];
        $note->update();

        $honorsStudentDiscount->amount = $requestData['amount'];
        $honorsStudentDiscount->gpa = $requestData['gpa'];
        $honorsStudentDiscount->update();

        return redirect('admin/honorsStudentDiscount')->with('flash_message', 'Блок изменен');
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
        $honorsStudentDiscount = HonorsStudentDiscount::find($id);
        $category = Translate::find($honorsStudentDiscount->category);
        $note = Translate::find($honorsStudentDiscount->note);
        $category->delete();
        $note->delete();
        $honorsStudentDiscount->delete();

        return redirect('admin/honorsStudentDiscount')->with('flash_message', 'Блок удален');
    }
}
