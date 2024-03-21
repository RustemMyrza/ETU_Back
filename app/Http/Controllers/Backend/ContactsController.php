<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Translate;
use Illuminate\Http\Request;
use App\Models\Contacts;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contacts = Contacts::first();
        return view('contacts.index', compact('contacts'));
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
        // file_put_contents('debug.log', 'IIIIUUUUUU', FILE_APPEND);
        // dd($requestData);
        $contacts = Contacts::first();
        // dd($contacts);
        // echo '<h1>$contacts->id</h1>';
        // echo '<pre>';
        // print_r($contacts->id);
        // echo '</pre>';
        // echo '<h1>$request[director_name]</h1>';
        // echo '<pre>';
        // print_r($request['director_name']);
        // echo '</pre>';
        if ($contacts) {
            $contacts->director_name = $requestData['director_name']['ru'];
            $contacts->director_phone_number = $requestData['director_phone_number']['ru'];
            $contacts->deputy_director_name = $requestData['deputy_director_name']['ru'];
            $contacts->deputy_director_phone_number = $requestData['deputy_director_phone_number']['ru'];
            $contacts->email = $requestData['email']['ru'];
            $contacts->reception = $requestData['reception']['ru'];
            $contacts->sales_department = $requestData['sales_department']['ru'];
            $contacts->email_elevator = $requestData['email_elevator']['ru'];
            $contacts->update();
            // $this->translateUpdate($contacts->id, $request['director_name']);
            // $this->translateUpdate($contacts->id, $request['director_phone_number']);
            // $this->translateUpdate($contacts->id, $request['deputy_director_name']);
            // $this->translateUpdate($contacts->id, $request['deputy_director_phone_number']);
            // $this->translateUpdate($contacts->id, $request['email']);
            // $this->translateUpdate($contacts->id, $request['reception']);
            // $this->translateUpdate($contacts->id, $request['sales_department']);
            // $this->translateUpdate($contacts->id, $request['email_elevator']);

        } else {
            // dd($request->all());
            // var_dump($requestData);
            Contacts::create([
                'director_name' => $requestData['director_name']['ru'],
                'director_phone_number' => $requestData['director_phone_number']['ru'],
                'deputy_director_name' => $requestData['deputy_director_name']['ru'],
                'deputy_director_phone_number' => $requestData['deputy_director_phone_number']['ru'],
                'email' => $requestData['email']['ru'],
                'reception' => $requestData['reception']['ru'],
                'sales_department' => $requestData['sales_department']['ru'],
                'email_elevator' => $requestData['email_elevator']['ru'],
            ]);
        }

        return redirect('admin/contacts')->with('success', 'Изменения сохранены');

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


    private function translateUpdate($id, $data)
    {
        // dd($data);
        Translate::find($id)->update([
            'ru'    =>  $data['ru'],
            'en'    =>  $data['en'],
            'kz'    =>  $data['kz']
        ]);
    }
}
