<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InfrastructureSlider;
use App\Models\Translate;

class InfrastructureSliderController extends Controller
{
    public function index()
    {
        $perPage = 25;
        $infrastructureSlider = InfrastructureSlider::latest()->paginate($perPage);
        return view('infrastructureSlider.index', compact('infrastructureSlider'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('infrastructureSlider.create');
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
        foreach($requestData as $key => $value)
        {
            if ($key != '_token' && $key != 'title')
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

        $title = new Translate();
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->save();
        $titleId = $title->id;


        $infrastructureSlider= new InfrastructureSlider();
        $infrastructureSlider->title = $titleId;
        $infrastructureSlider->images = json_encode($paths) ?? null;
        $infrastructureSlider->save();

        return redirect('admin/infrastructureSlider')->with('flash_message', 'Блок добавлен');
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
        $infrastructureSlider = InfrastructureSlider::findOrFail($id);
        return view('infrastructureSlider.show', compact('infrastructureSlider'));
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
        $infrastructureSlider = InfrastructureSlider::findOrFail($id);
        return view('infrastructureSlider.edit', compact('infrastructureSlider'));
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

        $infrastructureSlider = InfrastructureSlider::findOrFail($id);

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
        foreach($requestData as $key => $value)
        {
            if ($key != '_token' && $key != '_method')
            {
                if ($request->hasFile($key)) 
                {  
                    $paths[] = $this->uploadImage($request->file($key));
                    $infrastructureSlider->images = json_encode($paths);
                }
                else
                {
                    $infrastructureSlider->delete();
                }
            }
        }

        $title = Translate::find($infrastructureSlider->title);
        $title->ru = $requestData['title']['ru'];
        $title->en = $requestData['title']['en'];
        $title->kz = $requestData['title']['kz'];
        $title->update();

        $infrastructureSlider->update();

        return redirect('admin/infrastructureSlider')->with('flash_message', 'Блок изменен');
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
        $infrastructureSlider = InfrastructureSlider::find($id);
        $title = Translate::find($infrastructureSlider->title);
        foreach(json_decode($infrastructureSlider->images, 1) as $item)
        {
            unlink($item);
        }
        $title->delete();
        $infrastructureSlider->delete();

        return redirect('admin/infrastructureSlider')->with('flash_message', 'Блок удален');
    }
}
