<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FooterNavBarResource;
use App\Models\HeaderNavBar;
use App\Models\Contact;
use App\Http\Resources\HeaderNavBarResource;
use App\Http\Resources\ContactResource;


use function Ramsey\Uuid\v1;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function headerNavBar ()
    {
        $headerNavBarData = HeaderNavBar::query()->with('getName')->get();
        $headerNavBarResourceData = HeaderNavBarResource::collection($headerNavBarData);
        return $headerNavBarResourceData;
    }

    public function contacts ()
    {
        $contactsData = Contact::query()->with('getTabName')->get();
        return ContactResource::collection($contactsData);
    }
}
