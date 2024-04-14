<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translate;
use App\Models\BachelorSchool;
use App\Models\BachelorSchoolSpecialty;
use App\Models\BachelorSchoolEducator;
use App\Models\BachelorSpecialtyDocument;
use App\Models\BachelorSchoolPage;
use App\Models\BachelorSchoolSpecialtyPage;
use Illuminate\Support\Facades\Storage;

class BachelorSchoolController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $bachelorSchool = BachelorSchool::where('name', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $bachelorSchool = BachelorSchool::latest()->paginate($perPage);
            $translatesData = Translate::all();
        }
        // $this->getDataFromTable();
        return view('bachelorSchool.index', compact('bachelorSchool', 'translatesData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('bachelorSchool.create');
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

        $name = new Translate();
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->save();
        $nameId = $name->id;

        $bachelorSchool= new BachelorSchool();
        $bachelorSchool->name = $nameId;
        $bachelorSchool->image = $path ?? null;
        $bachelorSchool->save();

        return redirect('admin/bachelorSchool')->with('flash_message', 'Блок добавлен');
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
        $bachelorSchool = BachelorSchool::findOrFail($id);
        $translatedName = Translate::findOrFail($bachelorSchool->name);
        $translatedData['name'] = $translatedName;
        return view('bachelorSchool.show', compact('bachelorSchool', 'translatedData'));
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
        $bachelorSchool = BachelorSchool::findOrFail($id);
        $translatedName = Translate::findOrFail($bachelorSchool->name);
        $translatedData['name'] = $translatedName;
        return view('bachelorSchool.edit', compact('bachelorSchool', 'translatedData'));
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
        
        $bachelorSchool = BachelorSchool::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($bachelorSchool->image != null) {
                Storage::disk('static')->delete($bachelorSchool->image);
            }
            $path = $this->uploadImage($request->file('image'));
            $bachelorSchool->image = $path;
        }

        $name = Translate::find($bachelorSchool->name);
        $name->ru = $requestData['name']['ru'];
        $name->en = $requestData['name']['en'];
        $name->kz = $requestData['name']['kz'];
        $name->update();
        $bachelorSchool->update();

        return redirect('admin/bachelorSchool')->with('flash_message', 'Блок изменен');
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
        $bachelorSchool = BachelorSchool::find($id);
        $name = Translate::find($bachelorSchool->name);
        if ($bachelorSchool->image != null) {
            Storage::disk('static')->delete($bachelorSchool->image);
        }
        $specialties = BachelorSchoolSpecialty::where('school_id', $id)->get();
        $educators = BachelorSchoolEducator::where('school_id', $id)->get();
        $page = BachelorSchoolPage::where('school_id', $id)->get();
        if (count($specialties) > 0)
        {


            foreach($specialties as $item)
            {


                $specialtyPage = BachelorSchoolSpecialtyPage::where('specialty_id', $item->id)->get();
                $specialtyDocuments = BachelorSpecialtyDocument::where('specialty_id', $item->id)->get();


                if (count($specialtyPage) > 0)
                {
                    foreach ($specialtyPage as $pageItem)
                    {
                        if ($pageItem->title)
                        {
                            $specialtyPageTitle = Translate::findOrFail($pageItem->title);
                            $specialtyPageTitle->delete();
                        }
                        if ($pageItem->content)
                        {
                            $specialtyPageContent = Translate::findOrFail($pageItem->content);
                            $specialtyPageContent->delete();
                        }
                        if ($pageItem->image != null) {
                            Storage::disk('static')->delete($pageItem->image);
                        }
                        $pageItem->delete();
                    }
                }

                if (count($specialtyDocuments) > 0)
                {
                    foreach ($specialtyDocuments as $document)
                    {
                        if ($document->name)
                        {
                            $specialtyDocumentName = Translate::findOrFail($pageItem->title);
                            $specialtyDocumentName->delete();
                        }
                        if ($document->link != null) {
                            Storage::disk('static')->delete($document->link);
                        }
                        $document->delete();
                    }
                }



                if ($item->name)
                {

                    $specialtyName = Translate::findOrFail($item->name);
                    $specialtyName->delete();
                }



                $item->delete();
            }
        }




        if (count($educators) > 0)
        {
            foreach($educators as $item)
            {
                $educatorName = Translate::findOrFail($item->name);
                $educatorName->delete();
                if ($item->position)
                {
                    $position = Translate::findOrFail($item->position);
                    $position->delete();
                }
                if ($item->image != null) {
                    Storage::disk('static')->delete($item->image);
                }
                $item->delete();
            }
        }





        if (count($page) > 0)
        {
            foreach($page as $item)
            {
                if ($item->title)
                {
                    $pageTitle = Translate::findOrFail($item->title);
                    $pageTitle->delete();
                }
                if ($item->content)
                {
                    $pageContent = Translate::findOrFail($item->content);
                    $pageContent->delete();
                }
                if ($item->image != null) {
                    Storage::disk('static')->delete($item->image);
                }
                $item->delete();
            }
        }




        $name->delete();
        $bachelorSchool->delete();

        return redirect('admin/bachelorSchool')->with('flash_message', 'Блок удален');
    }
}
