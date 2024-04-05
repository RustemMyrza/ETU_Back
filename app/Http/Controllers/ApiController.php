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
use App\Models\RectorsBlogQuestion;
use App\Models\AcademicCouncilPage;
use App\Models\AcademicCouncilMember;
use App\Models\ScienceInnovationPage;
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
use App\Http\Resources\RectorsBlogQuestionResource;
use App\Http\Resources\AcademicCouncilPageResource;
use App\Http\Resources\AcademicCouncilMemberResource;
use App\Http\Resources\ScienceInnovationPageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use stdClass;

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
        $mainPageData = MainPage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($mainPageData as $key => $value)
        {
            switch($key){
                case 0:
                    $banner = new MainPageResource($value);
                    break;
                case 1:
                    $educationProgram['title'] = new MainPageResource($value);
                    break;
                case 2:
                    $educationProgram['items'] = new MainPageResource($value);
                    break;
                case 3:
                    $educationProgram['items'] = new MainPageResource($value);
                    break;
                case 4:
                    $professionalSchools['title'] = new MainPageResource($value);
                    break;
                case 5:
                    $professionalSchools['items'][] = new MainPageResource($value);
                    break;
                case 6:
                    $professionalSchools['items'][] = new MainPageResource($value);
                    break;
                case 7:
                    $professionalSchools['items'][] = new MainPageResource($value);
                    break;
                case 8:
                    $professionalSchools['items'][] = new MainPageResource($value);
                    break;
                case 9:
                    $professionalSchools['items'][] = new MainPageResource($value);
                    break;
                case 10:
                    $advantages['title'] = new MainPageResource($value);
                    break;
                case 11:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 12:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 13:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 14:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 15:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 16:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 17:
                    $inNumbers['title'] = new MainPageResource($value);
                    break;
                case 18:
                    $inNumbers['items'][] = new MainPageResource($value);
                    break;
                case 19:
                    $inNumbers['items'][] = new MainPageResource($value);
                    break;
                case 20:
                    $inNumbers['items'][] = new MainPageResource($value);
                    break;
                case 21:
                    $news = new MainPageResource($value);
                    break;
                case 22:
                    $application = new MainPageResource($value);
                    break;
                }
            }
        $mainPageApi = new stdClass;
        $mainPageApi->banner = $banner;
        $mainPageApi->educationProgram = $educationProgram;
        $mainPageApi->professionalSchools = $professionalSchools;
        $mainPageApi->advantages = $advantages;
        $mainPageApi->inNumbers = $inNumbers;
        $mainPageApi->inNumbers = $inNumbers;
        $mainPageApi->news = $news;
        $mainPageApi->application = $application;
        return $mainPageApi;
    }




    public function aboutUs ()
    {
        $aboutUs = AboutUsPage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($aboutUs as $key => $value)
        {
            switch($key){
                case 0:
                    $banner = new AboutUsPageResource($value);
                    break;
                case 1:
                    $history = new AboutUsPageResource($value);
                    break;
                case 2:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 3:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 4:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 5:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 6:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 7:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 8:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 9:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 10:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 11:
                    $historyYears[] = new AboutUsPageResource($value);
                    break;
                case 12:
                    $mission = new AboutUsPageResource($value);
                    break;
                case 13:
                    $vision = new AboutUsPageResource($value);
                    break;
        }
    }
        $aboutUsApi = new stdClass;
        $aboutUsApi->banner = $banner;
        $aboutUsApi->history = $history;
        $aboutUsApi->historyYears = $historyYears;
        $aboutUsApi->mission = $mission;
        $aboutUsApi->vision = $vision;
        return $aboutUsApi;
    }

    public function authority ()
    {
        $authority = AuthorityPage::query()->with(['getTitle', 'getContent'])->get();
        $supervisor = Supervisor::query()->with(['getName', 'getPosition', 'getAddress'])->get();
        $supervisors = SupervisorResource::collection($supervisor);
        foreach($authority as $key => $value)
        {
            switch($key){
                case 0:
                    $title = new AuthorityPageResource($value);
                    break;
            }            
        }
        $authorityApi = new stdClass;
        $authorityApi->title = $title;
        $authorityApi->supervisors = $supervisors;

        return $authorityApi;
    }

    public function accreditation (Request $request)
    {
        $accreditation = Accreditation::query()->with(['getTitle', 'getContent'])->get();

        $limit = $request->limit;
        $specialty = Specialty::query()->with(['getName'])->take($limit)->get();
        $specialties = SpecialtyResource::collection($specialty);

        foreach($accreditation as $key => $value)
        {
            switch($key)
            {
                case 0:
                    $titleDescription = new AccreditationResource($value);
                    break;
                case 1:
                    $button = new AccreditationResource($value);
                    break;
                case 2:
                    $documents[] = new AccreditationResource($value);
                    break;
                case 3:
                    $documents[] = new AccreditationResource($value);
                    break;
                case 4:
                    $documents[] = new AccreditationResource($value);
                    break;
                case 5:
                    $documents[] = new AccreditationResource($value);
                    break;
                case 6:
                    $documents[] = new AccreditationResource($value);
                    break;
                case 7:
                    $documents[] = new AccreditationResource($value);
                    break;
                case 8:
                    $documents[] = new AccreditationResource($value);
                    break;
            }
        }
        $accreditationApi = new stdClass;
        $accreditationApi->titleDescription = $titleDescription;
        $accreditationApi->specialties = $specialties;
        $accreditationApi->button = $button;
        $accreditationApi->documents = $documents;
        return $accreditationApi;
    }

    public function partnersPage ()
    {
        $partnersPage = PartnersPage::query()->with(['getTitle', 'getContent'])->get();

        $partners = $this->partner()['data'];

        foreach ($partnersPage as $key => $value)
        {
            switch($key)
            {
                case 0:
                    $title = new PartnersPageResource($value);
                    break;
                case 1:
                    $ourPartnersButton = new PartnersPageResource($value);
                    break;
                case 2:
                    $internationalPartnersButton = new PartnersPageResource($value);
                    break;
            }
        }

        $partnersPageApi = new stdClass;
        $partnersPageApi->title = $title;
        $partnersPageApi->ourPartnersButton = $ourPartnersButton;
        $partnersPageApi->internationalPartnersButton = $internationalPartnersButton;
        $partnersPageApi->partners = $partners;
        return $partnersPageApi;
    }

    private function partner ()
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

        return $data;
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

    public function rectorsBlogQuestion ()
    {
        $questions = RectorsBlogQuestion::query()->get();
        return RectorsBlogQuestionResource::collection($questions);
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

    public function scienceInnovationPage ()
    {
        $scienceInnovationPage = ScienceInnovationPage::query()->with(['getTitle', 'getContent'])->get();
        return ScienceInnovationPageResource::collection($scienceInnovationPage);
    }
}
