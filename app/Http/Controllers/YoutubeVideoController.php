<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YoutubeVideo;

class YoutubeVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $youtubeVideo = YoutubeVideo::first();
        return view('youtube.index', compact('youtubeVideo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        // dd($requestData);
        // file_put_contents('debug.log', 'IIIIUUUUUU', FILE_APPEND);
        // dd($requestData);
        $youtubeVideo = YoutubeVideo::first();
        // dd($youtubeVideo);
        // echo '<h1>$youtubeVideo->id</h1>';
        // echo '<pre>';
        // print_r($youtubeVideo->id);
        // echo '</pre>';
        // echo '<h1>$request[director_name]</h1>';
        // echo '<pre>';
        // print_r($request['director_name']);
        // echo '</pre>';
        if ($youtubeVideo) {
            $youtubeVideo->link = $requestData['link'];
            $youtubeVideo->update();
            // $this->translateUpdate($youtubeVideo->id, $request['director_name']);
            // $this->translateUpdate($youtubeVideo->id, $request['director_phone_number']);
            // $this->translateUpdate($youtubeVideo->id, $request['deputy_director_name']);
            // $this->translateUpdate($youtubeVideo->id, $request['deputy_director_phone_number']);
            // $this->translateUpdate($youtubeVideo->id, $request['email']);
            // $this->translateUpdate($youtubeVideo->id, $request['reception']);
            // $this->translateUpdate($youtubeVideo->id, $request['sales_department']);
            // $this->translateUpdate($youtubeVideo->id, $request['email_elevator']);

        } else {
            YoutubeVideo::create([
                'link' => $requestData['link'], 
            ]);
        }

        return redirect('admin/youtube')->with('success', 'Изменения сохранены');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
