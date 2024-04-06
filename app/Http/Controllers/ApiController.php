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
use App\Models\StudentScience;
use App\Models\ScientificPublicationPage;
use App\Models\AdmissionsCommitteePage;
use App\Models\MasterPage;
use App\Models\MastersSpecialty;
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
use App\Http\Resources\PageResource;
use App\Http\Resources\MastersSpecialtyResource;
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

        $vacancy = Vacancy::query()->get();
        $vacancies = VacancyResource::collection($vacancy);

        foreach ($careerPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new CareerPageResource($value);
                    break;
                case 1:
                    $infoBlocks[] = new CareerPageResource($value);
                    break;
                case 2:
                    $infoBlocks[] = new CareerPageResource($value);
                    break;
                case 3:
                    $infoBlocks[] = new CareerPageResource($value);
                    break;
                case 4:
                    $vacancyTitle = new CareerPageResource($value);
                    break;
                case 5:
                    $button = new CareerPageResource($value);
                    break;
            }
        }

        $careerPageApi = new stdClass;
        $careerPageApi->title = $title;
        $careerPageApi->infoBlocks = $infoBlocks;
        $careerPageApi->vacancyTitle = $vacancyTitle;
        $careerPageApi->vacancies = $vacancies;
        $careerPageApi->button = $button;
        return $careerPageApi;
    }

    public function rectorsBlogPage ()
    {
        $rectorsBlogPage = RectorsBlogPage::query()->with(['getTitle', 'getContent'])->get();

        $questions = RectorsBlogQuestion::query()->get();
        $questions = RectorsBlogQuestionResource::collection($questions);

        foreach ($rectorsBlogPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new RectorsBlogPageResource($value);
                    break;
                case 1:
                    $rector = new RectorsBlogPageResource($value);
                    break;
            }
        }
        $rectorsBlogPageApi = new stdClass;
        $rectorsBlogPageApi->title = $title;
        $rectorsBlogPageApi->rector = $rector;
        $rectorsBlogPageApi->questions = $questions;
        return $rectorsBlogPageApi;
    }

    public function academicCouncilPage ()
    {
        $academicCouncilPage = AcademicCouncilPage::query()->with(['getTitle', 'getContent'])->get();

        $academicCouncilMember = AcademicCouncilMember::query()->with(['getName', 'getDescription'])->get();
        $academicCouncilMembers = AcademicCouncilMemberResource::collection($academicCouncilMember);

        foreach ($academicCouncilPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new AcademicCouncilPageResource ($value);
                    break;
                case 1:
                    $infoBlock = new AcademicCouncilPageResource ($value);
                    break;
                case 2:
                    $images[] = new AcademicCouncilPageResource ($value);
                    break;
                case 3:
                    $images[] = new AcademicCouncilPageResource ($value);
                    break;
                case 4:
                    $images[] = new AcademicCouncilPageResource ($value);
                    break;
            }
        }

        $academicCouncilPageApi = new stdClass;
        $academicCouncilPageApi->title = $title;
        $academicCouncilPageApi->infoBlock = $infoBlock;
        $academicCouncilPageApi->images = $images;
        $academicCouncilPageApi->members = $academicCouncilMembers;
        return $academicCouncilPageApi;
    }

    public function academicCouncilMember ()
    {
        $academicCouncilMember = AcademicCouncilMember::query()->with(['getName', 'getDescription'])->get();
        return AcademicCouncilMemberResource::collection($academicCouncilMember);
    }

    public function scienceInnovationPage ()
    {
        $scienceInnovationPage = ScienceInnovationPage::query()->with(['getTitle', 'getContent'])->get();

        foreach ($scienceInnovationPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $scienceInnovationTitle = new ScienceInnovationPageResource ($value);
                    break;
                case 1:
                    $scienceBlock = new ScienceInnovationPageResource ($value);
                    break;
                case 2:
                    $innovationBlock = new ScienceInnovationPageResource ($value);
                    break;
                case 3:
                    $nipsTitle = new ScienceInnovationPageResource ($value);
                    break;
                case 4:
                    $nipsBlocks[] = new ScienceInnovationPageResource ($value);
                    break;
                case 5:
                    $nipsBlocks[] = new ScienceInnovationPageResource ($value);
                    break;
                case 6:
                    $nipsBlocks[] = new ScienceInnovationPageResource ($value);
                    break;
                case 7:
                    $nipsBlocks[] = new ScienceInnovationPageResource ($value);
                    break;
                case 8:
                    $nipsBlocks[] = new ScienceInnovationPageResource ($value);
                    break;
                case 9:
                    $nipsBlocks[] = new ScienceInnovationPageResource ($value);
                    break;
                case 10:
                    $normativeTitle = new ScienceInnovationPageResource ($value);
                    break;
                case 11:
                    $scienceConferentionTitle = new ScienceInnovationPageResource ($value);
                    break;
                case 12:
                    $scienceConferentionBlock = new ScienceInnovationPageResource ($value);
                    break;
            }
        }

        $scienceInnovationPageApi = new stdClass;
        $scienceInnovationPageApi->scienceInnovationTitle = $scienceInnovationTitle;
        $scienceInnovationPageApi->scienceBlock = $scienceBlock;
        $scienceInnovationPageApi->innovationBlock = $innovationBlock;
        $scienceInnovationPageApi->nipsTitle = $nipsTitle;
        $scienceInnovationPageApi->nipsBlocks = $nipsBlocks;
        $scienceInnovationPageApi->normativeTitle = $normativeTitle;
        $scienceInnovationPageApi->scienceConferentionTitle = $scienceConferentionTitle;
        $scienceInnovationPageApi->scienceConferentionBlock = $scienceConferentionBlock;
        return $scienceInnovationPageApi;
    }

    public function studentScience ()
    {
        $studentScience = StudentScience::query()->with(['getTitle', 'getContent'])->get();

        foreach ($studentScience as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $studentScienceBlock = new PageResource($value);
                    break;

            }
        }
        $studentScienceApi = new stdClass;
        $studentScienceApi->studentScienceBlock = $studentScienceBlock;
        return $studentScienceApi;
    }

    public function scientificPublicationPage ()
    {
        $scientificPublicationPage = ScientificPublicationPage::query()->with(['getTitle', 'getContent'])->get();

        foreach ($scientificPublicationPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource($value);
                    break;

            }
        }
        $scientificPublicationPageApi = new stdClass;
        $scientificPublicationPageApi->title = $title;
        return $scientificPublicationPageApi;
    }

    public function admissionsCommitteePage ()
    {
        $admissionsCommitteePage = AdmissionsCommitteePage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($admissionsCommitteePage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $admissionsCommitteeTitle = new PageResource($value);
                    break;
                case 1:
                    $discountsTitle = new PageResource($value);
                    break;
                case 2:
                    $tableTitle_1 = new PageResource($value);
                    break;
                case 3:
                    $tableTitle_2 = new PageResource($value);
                    break;
                case 4:
                    $tableTitle_3 = new PageResource($value);
                    break;
                case 5:
                    $costTitle = new PageResource($value);
                    break;
                case 6:
                    $tableTitle_4 = new PageResource($value);
                    break;
                case 7:
                    $tableTitle_5 = new PageResource($value);
                    break;
                case 8:
                    $listOfDocumentsTitle = new PageResource($value);
                    break;
                case 9:
                    $bachelorBlock = new PageResource($value);
                    break;
                case 10:
                    $masterBlock = new PageResource($value);
                    break;
            }
        }
        $scientificPublicationPageApi = new stdClass;
        $scientificPublicationPageApi->admissionsCommitteeTitle = $admissionsCommitteeTitle;
        $scientificPublicationPageApi->discountsTitle = $discountsTitle;
        $scientificPublicationPageApi->tableTitle_1 = $tableTitle_1;
        $scientificPublicationPageApi->tableTitle_2 = $tableTitle_2;
        $scientificPublicationPageApi->tableTitle_3 = $tableTitle_3;
        $scientificPublicationPageApi->costTitle = $costTitle;
        $scientificPublicationPageApi->tableTitle_4 = $tableTitle_4;
        $scientificPublicationPageApi->tableTitle_5 = $tableTitle_5;
        $scientificPublicationPageApi->listOfDocumentsTitle = $listOfDocumentsTitle;
        $scientificPublicationPageApi->bachelorBlock = $bachelorBlock;
        $scientificPublicationPageApi->masterBlock = $masterBlock;
        return $scientificPublicationPageApi;
    }

    public function masterPage ()
    {
        $masterPage = MasterPage::query()->with(['getTitle', 'getContent'])->get();

        $masterSpecialties = MastersSpecialty::query()->with(['getName', 'getPage'])->get();
        $masterSpecialties = MastersSpecialtyResource::collection($masterSpecialties);

        foreach ($masterPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
            }
        }
        $masterPageApi = new stdClass;
        $masterPageApi->title = $title;
        $masterPageApi->specalties = $masterSpecialties;
        return $masterPageApi;
    }
}
