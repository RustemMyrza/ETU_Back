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
use App\Models\Accreditation;
use App\Models\Specialty;
use App\Models\Partner;
use App\Models\PartnersPage;
use App\Models\CareerPage;
use App\Models\Vacancy;
use App\Models\RectorsBlogPage;
use App\Models\AcademicCouncilPage;
use App\Models\AcademicCouncilMember;
use App\Http\Resources\NewsResource;
use App\Http\Resources\HeaderNavBarResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\NewsPageResource;
use App\Http\Resources\MainPageResource;
use App\Http\Resources\AboutUsPageResource;
use App\Http\Resources\AuthorityPageResource;
use App\Http\Resources\SupervisorResource;
use App\Http\Resources\AccreditationResource;
use App\Http\Resources\SpecialtyResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\PartnersPageResource;
use App\Http\Resources\CareerPageResource;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\RectorsBlogPageResource;
use App\Http\Resources\AcademicCouncilPageResource;
use App\Http\Resources\AcademicCouncilMemberResource;
use Illuminate\Http\Request;

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

    public function accreditation ()
    {
        $accreditation = Accreditation::query()->with(['getTitle', 'getContent'])->get();
        return AccreditationResource::collection($accreditation);
    }

    public function specialty (Request $request)
    {
        $limit = $request->limit;
        $specialty = Specialty::query()->with(['getName'])->take($limit)->get();
        return SpecialtyResource::collection($specialty);
    }

    public function partnersPage ()
    {
        $partnersPage = PartnersPage::query()->with(['getTitle', 'getContent'])->get();
        return PartnersPageResource::collection($partnersPage);
    }

    public function partner ()
    {
        $partner = Partner::query()->get();

        $partnersByType = [];

        foreach ($partner as $item) {
            $partnersByType[$item->type][] = [
                'id' => $item->id,
                'name' => $item->name,
                'image' => $item->image,
                'type' => $item->type
            ];
        }

        $data['data'] = [];

        foreach ($partnersByType as $type => $partners) {
            $data['data'][$type] = array_chunk($partners, 8);
        }

        return response()->json($data, 200);
    }

    public function careerPage ()
    {
        $careerPage = CareerPage::query()->with(['getTitle', 'getContent'])->get();
        return CareerPageResource::collection($careerPage);
    }

    public function vacancy ()
    {
        $vacancy = Vacancy::query()->get();
        return VacancyResource::collection($vacancy);
    }

    public function rectorsBlogPage ()
    {
        $rectorsBlogPage = RectorsBlogPage::query()->with(['getTitle', 'getContent'])->get();
        return RectorsBlogPageResource::collection($rectorsBlogPage);
    }

    public function academicCouncilPage ()
    {
        $academicCouncilPage = AcademicCouncilPage::query()->with(['getTitle', 'getContent'])->get();
        return AcademicCouncilPageResource::collection($academicCouncilPage);
    }

    public function academicCouncilMember ()
    {
        $academicCouncilMember = AcademicCouncilMember::query()->with(['getName', 'getDescription'])->get();
        return AcademicCouncilMemberResource::collection($academicCouncilMember);
    }
}
