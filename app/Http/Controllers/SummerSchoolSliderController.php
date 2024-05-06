<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SummerSchoolSlider;

class SummerSchoolSliderController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $slider = SummerSchoolSlider::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } 
        else 
        {
            $slider = SummerSchoolSlider::latest()->paginate($perPage);
        }
        // $this->getDataFromTable();
        return view('summerSchoolSlider.index', compact('slider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // dd($newsId);
        return view('summerSchoolSlider.create');
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
        $requestData = $request->all();
        foreach($requestData as $key => $value)
        {
            if ($key != '_token')
            {
                $request->validate([
                    $key => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                ],
                [
                    $key . '.required' => 'Изображение для блока обязательно',
                    $key. '.mimes' => 'Проверьте формат изображения',
                    $key . '.max' => 'Размер файла не может превышать 10МБ'
                ]);
            }
        }

        foreach($requestData as $key => $value)
        {
            if ($key != '_token')
            {
                if ($request->hasFile($key)) 
                {
                    $paths[] = $this->uploadImage($request->file($key));
                }
            }
        }



        $slider= new SummerSchoolSlider();
        $slider->images = json_encode($paths) ?? null;
        $slider->save();

        return redirect('admin/summerSchoolSlider')->with('flash_message', 'Блок добавлен');
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
        $slider = SummerSchoolSlider::findOrFail($id);
        return view('summerSchoolSlider.show', compact('slider'));
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
        $slider = SummerSchoolSlider::findOrFail($id);
        return view('summerSchoolSlider.edit', compact('slider'));
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
        // dd($requestData);
        foreach($requestData as $key => $value)
        {
            if ($key != '_token' && $key != '_method')
            {
                $request->validate([
                    $key => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                ],
                [
                    $key . '.required' => 'Изображение для блока обязательно',
                    $key. '.mimes' => 'Проверьте формат изображения',
                    $key . '.max' => 'Размер файла не может превышать 10МБ'
                ]);
            }
        }
        // dd($id);
        $slider = SummerSchoolSlider::findOrFail($id);
        foreach($requestData as $key => $value)
        {
            if ($key != '_token' && $key != '_method')
            {
                if ($request->hasFile($key)) 
                {  
                    foreach(json_decode($slider->images) as $item)
                    {
                        unlink($item);
                    }
                    
                    $paths[] = $this->uploadImage($request->file($key));
                    $slider->images = json_encode($paths);
                }
                else
                {
                    foreach(json_decode($slider->images) as $item)
                    {
                        unlink($item);
                    }
                
                    $slider->delete();
                }
            }
        }
        $slider->update();

        return redirect('admin/summerSchoolSlider')->with('flash_message', 'Блок изменен');
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
        $slider = SummerSchoolSlider::find($id);
        if ($slider->image != null) {
            unlink($slider->image);
        }

        $slider->delete();

        return redirect('admin/summerSchoolSlider')->with('flash_message', 'Блок удален');
    }
}
