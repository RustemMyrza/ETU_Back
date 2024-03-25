<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Translate;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contacts = Contact::first();
        return view('contact.index', compact('contacts'));
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
        $contacts = Contact::first();
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
            $translateTabName = new Translate();
            $translateTabName->ru = 'Контакты';
            $translateTabName->kz = 'Байланыс';
            $translateTabName->en = 'Contacts';
            $translateTabName->save();
            $contacts->tab_name = $translateTabName->id;
            $contacts->address = $requestData['address'];
            $contacts->admissions_committee_num_1 = $requestData['admissions_committee_num_1'];
            $contacts->admissions_committee_num_2 = $requestData['admissions_committee_num_2'];
            $contacts->admissions_committee_mail = $requestData['admissions_committee_mail'];
            $contacts->rectors_reception_num = $requestData['rectors_reception_num'];
            $contacts->office_receptionist_num = $requestData['office_receptionist_num'];
            $contacts->tiktok_name = $requestData['tiktok_name'];
            $contacts->tiktok_link = $requestData['tiktok_link'];
            $contacts->instagram_name = $requestData['instagram_name'];
            $contacts->instagram_link = $requestData['instagram_link'];
            $contacts->facebook_link = $requestData['facebook_link'];
            $contacts->youtube_link = $requestData['youtube_link'];
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
            $translateTabName = new Translate();
            $translateTabName->ru = 'Контакты';
            $translateTabName->kz = 'Байланыс';
            $translateTabName->en = 'Contacts';
            $translateTabName->save();
            Contact::create([
                'tab_name' => $translateTabName->id, 
                'address' => $requestData['address'],
                'admissions_committee_num_1' => $requestData['admissions_committee_num_1'],
                'admissions_committee_num_2' => $requestData['admissions_committee_num_2'],
                'admissions_committee_mail' => $requestData['admissions_committee_mail'],
                'rectors_reception_num' => $requestData['rectors_reception_num'],
                'office_receptionist_num' => $requestData['office_receptionist_num'],
                'tiktok_name' => $requestData['tiktok_name'],
                'tiktok_link' => $requestData['tiktok_link'],
                'instagram_name' => $requestData['instagram_name'],
                'instagram_link' => $requestData['instagram_link'],
                'facebook_link' => $requestData['facebook_link'],
                'youtube_link' => $requestData['youtube_link']
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
}
