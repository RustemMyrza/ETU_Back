<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FooterNavBarResource;
use App\Models\HeaderNavBar;
use App\Models\Contact;
use App\Models\Translate;
use App\Http\Resources\HeaderNavBarResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\NewsPageResource;

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
        return HeaderNavBarResource::collection($headerNavBarData);
    }

    public function contacts ()
    {
        $contactsData = Contact::query()->with('getTabName')->get();
        return ContactResource::collection($contactsData);
    }

    public function newsPage ()
    {
        $newsPageData = Translate::where('id', 175)->get();
        return NewsPageResource::collection($newsPageData);
    }
}
