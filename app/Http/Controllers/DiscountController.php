<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Translate;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $discount = Discount::where('category', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $discount = Discount::latest()->paginate($perPage);
            $forTypeStudents = [
                1 => 'Для абитуриентов',
                2 => 'Для поступающих в магистратуру'
            ];
            $translatesData = Translate::all();
        }

        return view('discount.index', compact('discount', 'translatesData', 'forTypeStudents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('discount.create');
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


        $discount= new Discount();
        $discount->category = $categoryId;
        $discount->note = $noteId;
        $discount->amount = $requestData['amount'];
        $discount->student_type = $requestData['student_type'];
        $discount->save();

        return redirect('admin/discount')->with('flash_message', 'Блок добавлен');
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
        $discount = Discount::findOrFail($id);
        $translatedCategory = Translate::findOrFail($discount->category);
        $translatedNote = Translate::findOrFail($discount->note);
        $translatedData['category'] = $translatedCategory;
        $translatedData['note'] = $translatedNote;
        $forTypeStudents = [
            1 => 'Для абитуриентов',
            2 => 'Для поступающих в магистратуру'
        ];
        return view('discount.show', compact('discount', 'translatedData', 'forTypeStudents'));
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
        $discount = Discount::findOrFail($id);
        $translatedCategory = Translate::findOrFail($discount->category);
        $translatedNote = Translate::findOrFail($discount->note);
        $translatedData['category'] = $translatedCategory;
        $translatedData['note'] = $translatedNote;
        return view('discount.edit', compact('discount', 'translatedData'));
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
        $discount = Discount::findOrFail($id);

        $category = Translate::find($discount->category);
        $category->ru = $requestData['category']['ru'];
        $category->en = $requestData['category']['en'];
        $category->kz = $requestData['category']['kz'];
        $category->update();

        $note = Translate::find($discount->note);
        $note->ru = $requestData['note']['ru'];
        $note->en = $requestData['note']['en'];
        $note->kz = $requestData['note']['kz'];
        $note->update();

        $discount->amount = $requestData['amount'];
        $discount->student_type = $requestData['student_type'];
        $discount->update();

        return redirect('admin/discount')->with('flash_message', 'Блок изменен');
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
        $discount = Discount::find($id);
        $category = Translate::find($discount->category);
        $note = Translate::find($discount->note);
        $category->delete();
        $note->delete();
        $discount->delete();

        return redirect('admin/discount')->with('flash_message', 'Блок удален');
    }
}
