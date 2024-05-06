<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsPageSlider;
use App\Models\News;

class NewsPageSliderController extends Controller
{
    public function index(Request $request, $newsId)
    {
        // dd($newsId);
        $newsName = News::findOrFail($newsId)->with('getName')->get()[0]->getName->ru;
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $slider = NewsPageSlider::where('content', 'LIKE', "%$keyword%")
                ->orWhere('image', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } 
        else 
        {
            $slider = NewsPageSlider::where('news_id', $newsId)
                ->latest()
                ->paginate($perPage);
        }
        // $this->getDataFromTable();
        return view('newsPageSlider.index', compact('slider', 'newsId', 'newsName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create($newsId)
    {
        // dd($newsId);
        return view('newsPageSlider.create', compact('newsId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $newsId)
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



        $slider= new NewsPageSlider();
        $slider->images = json_encode($paths) ?? null;
        $slider->news_id = $newsId;
        $slider->save();

        return redirect('admin/news/' . $newsId . '/slider')->with('flash_message', 'Блок добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($newsId, $id)
    {
        $slider = NewsPageSlider::findOrFail($id);
        return view('newsPageSlider.show', compact('slider', 'newsId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($newsId, $id)
    {
        $slider = NewsPageSlider::findOrFail($id);
        return view('newsPageSlider.edit', compact('slider', 'newsId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $newsId, $id)
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
        $slider = NewsPageSlider::findOrFail($id);
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

        return redirect('admin/news/' . $newsId . '/slider')->with('flash_message', 'Блок изменен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($newsId, $id)
    {
        $slider = NewsPageSlider::find($id);
        if ($slider->image != null) {
            unlink($slider->image);
        }

        $slider->delete();

        return redirect('admin/news/' . $newsId . '/slider')->with('flash_message', 'Блок удален');
    }
}
