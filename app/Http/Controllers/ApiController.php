<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HeaderNavBar;
use App\Models\Contact;
use App\Models\Translate;
use App\Models\News;
use App\Models\MainPage;
use App\Models\AboutUsPage;
use App\Models\AuthorityPage;
use App\Models\Supervisor;
use App\Http\Resources\NewsResource;
use App\Http\Resources\HeaderNavBarResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\NewsPageResource;
use App\Http\Resources\MainPageResource;
use App\Http\Resources\AboutUsPageResource;
use App\Http\Resources\AuthorityPageResource;
use App\Http\Resources\SupervisorResource;

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

    public function news ()
    {
        $news = News::query()->with(['getName', 'getChild'])->get();
        return NewsResource::collection($news);
    }

    public function mainPage ()
    {
        $mainPage = MainPage::query()->with(['getTitle', 'getContent'])->get();
        return MainPageResource::collection($mainPage);
    }

    public function aboutUs ()
    {
        $aboutUs = AboutUsPage::query()->with(['getTitle', 'getContent'])->get();
        return AboutUsPageResource::collection($aboutUs);
    }

    public function authority ()
    {
        $authority = AuthorityPage::query()->with(['getTitle', 'getContent'])->get();
        return AuthorityPageResource::collection($authority);
    }

    public function supervisor ()
    {
        $supervisor = Supervisor::query()->with(['getName', 'getPosition', 'getAddress'])->get();
        return SupervisorResource::collection($supervisor);
    }
}
