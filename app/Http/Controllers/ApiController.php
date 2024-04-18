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
use App\Models\InternationalStudentsPage;
use App\Models\LanguageCoursesPage;
use App\Models\MajorMinorPage;
use App\Models\LevelUpPage;
use App\Models\OlympicsPage;
use App\Models\LincolnUniversityPage;
use App\Models\AcademicPolicyPage;
use App\Models\AcademicCalendarPage;
use App\Models\LibraryPage;
use App\Models\EthicsCodePage;
use App\Models\CareerCenterPage;
use App\Models\MilitaryDepartmentPage;
use App\Models\MedicalCarePage;
use App\Models\StudentHousePage;
use App\Models\Dormitory;
use App\Models\TravelGuidePage;
use App\Models\StudentClubPage;
use App\Models\StudentClub;
use App\Models\BachelorSchool;
use App\Models\BachelorSchoolEducator;
use App\Models\BachelorSchoolPage;
use App\Models\BachelorSchoolSpecialty;
use App\Models\BachelorSchoolSpecialtyPage;
use App\Models\AcademicPolicyPageDocument;
use App\Models\AcademicCalendarPageDocument;
use App\Models\AccreditationPageDocument;
use App\Models\AdmissionsCommitteePageDocument;
use App\Models\BachelorSpecialtyDocument;
use App\Models\EthicalCodePageDocument;
use App\Models\InternationalStudentsPageDocument;
use App\Models\LincolnUniversityPageDocument;
use App\Models\MasterSpecialtyPageDocument;
use App\Models\ScienceInnovationPageDocument;
use App\Models\OlympicsPageDocument;
use App\Models\ScientificPublicationPageDocument;
use App\Models\StudentSciencePageDocument;
use App\Models\TravelGuidePageDocument;
use App\Models\ScienceAboutPage;
use App\Models\SummerSchoolPage;
use App\Models\SummerSchoolDocument;
use App\Models\SummerSchoolProgram;
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
use App\Http\Resources\DormitoryResource;
use App\Http\Resources\StudentClubResource;
use App\Http\Resources\BachelorSchoolResource;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\ScientificPublicationResource;
use App\Http\Resources\MainPageSchoolResource;
use App\Http\Resources\SummerSchoolProgramResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Block\Element\Document;
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
        return ContactResource::collection($contactsData)[0];
    }

    public function newsPage ()
    {
        $newsPageData = Translate::where('id', 175)->get();
        return NewsPageResource::collection($newsPageData);
    }



    private function sortByDateOldToNew($a, $b) 
    {
        return strtotime($a->date) - strtotime($b->date);
    }



    private function sortByDateNewToOld($a, $b) 
    {
        return strtotime($b->date) - strtotime($a->date);
    }



    private function sorted($data, $type) 
    {
        if ($type == 'old')
        {
            usort($data, [$this, 'sortByDateOldToNew']);
        }
        else
        {
            usort($data, [$this, 'sortByDateNewToOld']);
        }
        return $data;
    }




    public function news (Request $request)
    {
        $correctNews = [];
        $news = News::query()->with(['getName', 'getChild'])->get();
        $year = $request->input('year');
        $month = $request->input('month');
        $day = $request->input('day');
        $sort = $request->input('sort');

        $requestDate = [$year, $month, $day];

        foreach ($news as $item)
        {
            $correctCount = 0;
            $dateArr = explode('-', $item->date);
            
            foreach ($requestDate as $key => $value)
            {
                if ($value)
                {
                    if ($dateArr[$key] == $value)
                    {
                        $correctCount += 1; 
                    }
                }
                else
                {
                    $correctCount += 1;
                }
            }

            if($correctCount == 3)
            {
                $correctNews[] = $item;
            }
        }
        
        $sortedCorrectNews = $this->sorted($correctNews, $sort);
        return NewsResource::collection($sortedCorrectNews);
    }




    public function mainPage ()
    {
        $mainPageData = MainPage::query()->with(['getTitle', 'getContent'])->get();
        $schools = BachelorSchool::query()->with(['getName'])->get();
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
                    $educationProgram['items'][] = new MainPageResource($value);
                    break;
                case 3:
                    $educationProgram['items'][] = new MainPageResource($value);
                    break;
                case 4:
                    $professionalSchools['title'] = new MainPageResource($value);
                    break;
                case 5:
                    $advantages['title'] = new MainPageResource($value);
                    break;
                case 6:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 7:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 8:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 9:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 10:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 11:
                    $advantages['items'][] = new MainPageResource($value);
                    break;
                case 12:
                    $inNumbers['title'] = new MainPageResource($value);
                    break;
                case 13:
                    $inNumbers['items'][] = new MainPageResource($value);
                    break;
                case 14:
                    $inNumbers['items'][] = new MainPageResource($value);
                    break;
                case 15:
                    $inNumbers['items'][] = new MainPageResource($value);
                    break;
                case 16:
                    $news = new MainPageResource($value);
                    break;
                case 17:
                    $applicationTitle = new MainPageResource($value);
                    break;
                case 18:
                    $faqTitle = new MainPageResource($value);
                    break;
                case 19:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 20:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 21:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 22:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 23:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 24:
                    $applicationDescription = new MainPageResource($value);
                    break;
                case 25:
                    $applicationButton = new MainPageResource($value);
                    break;
                }
            }

        $schoolsResource = MainPageSchoolResource::collection($schools);
        $professionalSchools['item'] = $schoolsResource;
        $request = new Request(); // Создаем новый экземпляр объекта Request
        $request->merge(['sort' => 'new']);
        $mainPageApi = new stdClass;
        $mainPageApi->banner = $banner;
        $mainPageApi->educationProgram = $educationProgram;
        $mainPageApi->professionalSchools = $professionalSchools;
        $mainPageApi->advantages = $advantages;
        $mainPageApi->inNumbers = $inNumbers;
        $mainPageApi->inNumbers = $inNumbers;
        $mainPageApi->news = $news;
        $newsItems = $this->news($request);
        $mainPageApi->newsItems = $newsItems;
        $mainPageApi->application = new stdClass;
        $mainPageApi->application->title = $applicationTitle;
        $mainPageApi->application->description = $applicationDescription;
        $mainPageApi->application->button = $applicationButton;
        $mainPageApi->faqTitle = $faqTitle;
        $mainPageApi->faqQuestions = $faqQuestions;
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
                    $blocks[] = new AboutUsPageResource($value);
                    break;
                case 13:
                    $blocks[] = new AboutUsPageResource($value);
                    break;
        }
    }
        $aboutUsApi = new stdClass;
        $aboutUsApi->banner = $banner;
        $aboutUsApi->history = $history;
        $aboutUsApi->historyYears = $historyYears;
        $aboutUsApi->blocks = $blocks;
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
        $documents = AccreditationPageDocument::query()->with(['getName'])->get();
        $resourceDocuments = DocumentResource::collection($documents);
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
                    $documentsDownloadButton = new AccreditationResource($value);
                    break;
            }
        }
        $accreditationApi = new stdClass;
        $accreditationApi->titleDescription = $titleDescription;
        $accreditationApi->specialties = $specialties;
        $accreditationApi->button = $button;
        $accreditationApi->documents = $resourceDocuments;
        $accreditationApi->documentsDownloadButton = $documentsDownloadButton;
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
            if ($item->type == 1)
            {
                $partnersByType['aboutPartnerOurItems'][] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image,
                    'type' => $item->type
                ];
            }
            else
            {
                $partnersByType['aboutInternationalPartnersItems'][] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image,
                    'type' => $item->type
                ];
            }
        }

        $data['data'] = [];

        foreach ($partnersByType as $type => $partners) {
            $data['data'][$type] = $partners;
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
        
        foreach ($questions as $item)
        {
            if ($item->answer)
            {
                $answeredQuestions[] = new RectorsBlogQuestionResource($item);
            }
        }

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
        $rectorsBlogPageApi->questions = $answeredQuestions;
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
        $documents = ScienceInnovationPageDocument::query()->with(['getName'])->get();

        foreach ($documents as $item)
        {
            if ($item->block_id == 1)
            {
                $normativeDocuments[] = new DocumentResource ($item);
            }
            else
            {
                $scienceConferentionDocuments[] = new DocumentResource ($item);
            }
        }


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
        $scienceInnovationPageApi->normativeDocuments = $normativeDocuments;
        $scienceInnovationPageApi->scienceConferentionTitle = $scienceConferentionTitle;
        $scienceInnovationPageApi->scienceConferentionBlock = $scienceConferentionBlock;
        $scienceInnovationPageApi->scienceConferentionDocuments = $scienceConferentionDocuments;
        return $scienceInnovationPageApi;
    }

    public function studentScience ()
    {
        $studentScience = StudentScience::query()->with(['getTitle', 'getContent'])->get();
        $documents = StudentSciencePageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

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
        $studentScienceApi->documents = $documentsResource;
        return $studentScienceApi;
    }

    public function scientificPublicationPage ()
    {
        $scientificPublicationPage = ScientificPublicationPage::query()->with(['getTitle', 'getContent'])->get();
        $documents = ScientificPublicationPageDocument::query()->with(['getName', 'getAuthor'])->get();
        $documentsResource = ScientificPublicationResource::collection($documents);

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
        $scientificPublicationPageApi->documents = $documentsResource;
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

    public function internationalStudentsPage ()
    {
        $internationalStudentsPage = InternationalStudentsPage::query()->with(['getTitle', 'getContent'])->get();

        $documents = InternationalStudentsPageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

        foreach ($internationalStudentsPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $welcomeBlock = new PageResource ($value);
                    break;
                case 2:
                    $welcomeText = new PageResource ($value);
                    break;
                case 3:
                    $usefulInfoTitle = new PageResource ($value);
                    break;
                case 4:
                    $usefulInfoBlocks[] = new PageResource ($value);
                    break;
                case 5:
                    $usefulInfoBlocks[] = new PageResource ($value);
                    break;
                case 6:
                    $usefulInfoBlocks[] = new PageResource ($value);
                    break;
                case 7:
                    $usefulInfoBlocks[] = new PageResource ($value);
                    break;
            }
        }
        $internationalStudentsPageApi = new stdClass;
        $internationalStudentsPageApi->title = $title;
        $internationalStudentsPageApi->welcomeBlock = $welcomeBlock;
        $internationalStudentsPageApi->welcomeText = $welcomeText;
        $internationalStudentsPageApi->usefulInfoTitle = $usefulInfoTitle;
        $internationalStudentsPageApi->usefulInfoBlocks = $usefulInfoBlocks;
        $internationalStudentsPageApi->documents = $documentsResource;
        return $internationalStudentsPageApi;
    }

    public function languageCoursesPage ()
    {
        $languageCoursesPage = LanguageCoursesPage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($languageCoursesPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $languageCourseBlock = new PageResource ($value);
                    break;
            }
        }
        $languageCoursesPageApi = new stdClass;
        $languageCoursesPageApi->languageCourseBlock = $languageCourseBlock;
        return $languageCoursesPageApi;
    }

    public function majorMinorPage ()
    {
        $majorMinorPage = MajorMinorPage::query()->with(['getTitle', 'getContent'])->get();

        foreach ($majorMinorPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $majorMinorBlock = new PageResource ($value);
                    break;
            }
        }
        $majorMinorPageApi = new stdClass;
        $majorMinorPageApi->majorMinorBlock = $majorMinorBlock;
        return $majorMinorPageApi;
    }

    public function levelUpPage ()
    {
        $levelUpPage = LevelUpPage::query()->with(['getTitle', 'getContent'])->get();

        foreach ($levelUpPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $levelUpBlock = new PageResource ($value);
                    break;
            }
        }
        $levelUpPageApi = new stdClass;
        $levelUpPageApi->levelUpBlock = $levelUpBlock;
        return $levelUpPageApi;
    }

    public function olympicsPage ()
    {
        $olympicsPage = OlympicsPage::query()->with(['getTitle', 'getContent'])->get();

        $documents = OlympicsPageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);
        
        foreach ($olympicsPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $olympicsEtuBlock = new PageResource ($value);
                    break;
                case 2:
                    $olympicsGoal = new PageResource ($value);
                    break;
                case 3:
                    $participateInvitation = new PageResource ($value);
                    break;
                case 4:
                    $olympiadStage = new PageResource ($value);
                    break;
                case 5:
                    $olympiadResults[] = new PageResource ($value);
                    break;
                case 6:
                    $olympiadResults[] = new PageResource ($value);
                    break;
                case 7:
                    $olympiadResults[] = new PageResource ($value);
                    break;
                case 8:
                    $olympiadResults[] = new PageResource ($value);
                    break;
                case 9:
                    $olympiadResultsTitle = new PageResource ($value);
                    break;
                case 10:
                    $olympiadInfoTitle = new PageResource ($value);
                    break;
                case 11:
                    $olympiadInfoText = new PageResource ($value);
                    break;
            }
        }
        $olympicsPageApi = new stdClass;
        $olympicsPageApi->title = $title;
        $olympicsPageApi->olympicsEtuBlock = $olympicsEtuBlock;
        $olympicsPageApi->olympicsGoal = $olympicsGoal;
        $olympicsPageApi->participateInvitation = $participateInvitation;
        $olympicsPageApi->olympiadStage = $olympiadStage;
        $olympicsPageApi->olympiadResultsTitle = $olympiadResultsTitle;
        $olympicsPageApi->olympiadResults = $olympiadResults;
        $olympicsPageApi->olympiadInfoTitle = $olympiadInfoTitle;
        $olympicsPageApi->olympiadInfoText = $olympiadInfoText;
        $olympicsPageApi->documents = $documentsResource;
        return $olympicsPageApi;
    }

    public function lincolnUniversityPage ()
    {
        $lincolnUniversityPage = LincolnUniversityPage::query()->with(['getTitle', 'getContent'])->get();

        $documents = LincolnUniversityPageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

        foreach ($lincolnUniversityPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $aboutProgramBlock = new PageResource ($value);
                    break;
                case 2:
                    $fullSupport = new PageResource ($value);
                    break;
                case 3:
                    $financialBenefit = new PageResource ($value);
                    break;
                case 4:
                    $safetyAndComfort = new PageResource ($value);
                    break;
                case 5:
                    $bestOf2 = new PageResource ($value);
                    break;
                case 6:
                    $majorDisciplineTitle = new PageResource ($value);
                    break;
                case 7:
                    $majorDisciplines[] = new PageResource ($value);
                    break;
                case 8:
                    $majorDisciplines[] = new PageResource ($value);
                    break;
                case 9:
                    $majorDisciplines[] = new PageResource ($value);
                    break;
                case 10:
                    $majorDisciplines[] = new PageResource ($value);
                    break;
                case 11:
                    $majorDisciplines[] = new PageResource ($value);
                    break;
                case 12:
                    $programDetailTitle = new PageResource ($value);
                    break;
                case 13:
                    $programDetail[] = new PageResource ($value);
                    break;
                case 14:
                    $programDetail[] = new PageResource ($value);
                    break;
                case 15:
                    $programDetail[] = new PageResource ($value);
                    break;
                case 16:
                    $consultButton = new PageResource ($value);
                    break;
                case 17:
                    $downloadButton = new PageResource ($value);
                    break;
            }
        }
        $lincolnUniversityPageApi = new stdClass;
        $lincolnUniversityPageApi->title = $title;
        $lincolnUniversityPageApi->aboutProgramBlock = $aboutProgramBlock;
        $lincolnUniversityPageApi->fullSupport = $fullSupport;
        $lincolnUniversityPageApi->financialBenefit = $financialBenefit;
        $lincolnUniversityPageApi->safetyAndComfort = $safetyAndComfort;
        $lincolnUniversityPageApi->bestOf2 = $bestOf2;
        $lincolnUniversityPageApi->majorDisciplineTitle = $majorDisciplineTitle;
        $lincolnUniversityPageApi->majorDisciplines = $majorDisciplines;
        $lincolnUniversityPageApi->programDetailTitle = $programDetailTitle;
        $lincolnUniversityPageApi->programDetail = $programDetail;
        $lincolnUniversityPageApi->consultButton = $consultButton;
        $lincolnUniversityPageApi->downloadButton = $downloadButton;
        $lincolnUniversityPageApi->documents = $documentsResource;
        return $lincolnUniversityPageApi;
    }

    public function academicPolicyPage ()
    {
        $academicPolicyPage = AcademicPolicyPage::query()->with(['getTitle', 'getContent'])->get();

        $documents = AcademicPolicyPageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

        foreach ($academicPolicyPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $text = new PageResource ($value);
                    break;
            }
        }
        $academicPolicyPageApi = new stdClass;
        $academicPolicyPageApi->title = $title;
        $academicPolicyPageApi->text = $text;
        $academicPolicyPageApi->documents = $documentsResource;
        return $academicPolicyPageApi;
    }

    public function academicCalendarPage ()
    {
        $academicCalendarPage = AcademicCalendarPage::query()->with(['getTitle', 'getContent'])->get();

        $documents = AcademicCalendarPageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

        foreach ($academicCalendarPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $block = new PageResource ($value);
                    break;
            }
        }
        $academicCalendarPageApi = new stdClass;
        $academicCalendarPageApi->title = $title;
        $academicCalendarPageApi->block = $block;
        $academicCalendarPageApi->documents = $documentsResource;
        return $academicCalendarPageApi;
    }

    public function libraryPage ()
    {
        $libraryPage = LibraryPage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($libraryPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $aboutLibraryBlock = new PageResource ($value);
                    break;
                case 2:
                    $aboutLibraryText = new PageResource ($value);
                    break;
                case 3:
                    $button = new PageResource ($value);
                    break;
            }
        }
        $libraryPageApi = new stdClass;
        $libraryPageApi->title = $title;
        $libraryPageApi->aboutLibraryBlock = $aboutLibraryBlock;
        $libraryPageApi->aboutLibraryText = $aboutLibraryText;
        $libraryPageApi->button = $button;
        return $libraryPageApi;
    }

    public function ethicsCodePage ()
    {
        $ethicsCodePage = EthicsCodePage::query()->with(['getTitle', 'getContent'])->get();

        $documents = EthicalCodePageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

        foreach ($ethicsCodePage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $block = new PageResource ($value);
                    break;
            }
        }
        $ethicsCodePageApi = new stdClass;
        $ethicsCodePageApi->title = $title;
        $ethicsCodePageApi->block = $block;
        $ethicsCodePageApi->documents = $documentsResource;
        return $ethicsCodePageApi;
    }

    public function careerCenterPage ()
    {
        $careerCenterPage = CareerCenterPage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($careerCenterPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $aboutCareerCenterBlock = new PageResource ($value);
                    break;
                case 2:
                    $tasksBlock = new PageResource ($value);
                    break;
                case 3:
                    $functionsBlock = new PageResource ($value);
                    break;
                case 4:
                    $consultBlock = new PageResource ($value);
                    break;
            }
        }
        $careerCenterPageApi = new stdClass;
        $careerCenterPageApi->title = $title;
        $careerCenterPageApi->aboutCareerCenterBlock = $aboutCareerCenterBlock;
        $careerCenterPageApi->tasksBlock = $tasksBlock;
        $careerCenterPageApi->functionsBlock = $functionsBlock;
        $careerCenterPageApi->consultBlock = $consultBlock;
        return $careerCenterPageApi;
    }

    public function militaryDepartmentPage ()
    {
        $militaryDepartmentPage = MilitaryDepartmentPage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($militaryDepartmentPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $text = new PageResource ($value);
                    break;
                case 2:
                    $addressBlock = new PageResource ($value);
                    break;
                case 3:
                    $contactBlock = new PageResource ($value);
                    break;
            }
        }
        $militaryDepartmentPageApi = new stdClass;
        $militaryDepartmentPageApi->title = $title;
        $militaryDepartmentPageApi->text = $text;
        $militaryDepartmentPageApi->addressBlock = $addressBlock;
        $militaryDepartmentPageApi->contactBlock = $contactBlock;
        return $militaryDepartmentPageApi;
    }

    public function medicalCarePage ()
    {
        $medicalCarePage = MedicalCarePage::query()->with(['getTitle', 'getContent'])->get();
        foreach ($medicalCarePage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $addressBlock = new PageResource ($value);
                    break;
                case 2:
                    $instructionBlock = new PageResource ($value);
                    break;
                case 3:
                    $contactBlock = new PageResource ($value);
                    break;
            }
        }
        $medicalCarePageApi = new stdClass;
        $medicalCarePageApi->title = $title;
        $medicalCarePageApi->addressBlock = $addressBlock;
        $medicalCarePageApi->instructionBlock = $instructionBlock;
        $medicalCarePageApi->contactBlock = $contactBlock;
        return $medicalCarePageApi;
    }

    public function studentHousePage ()
    {
        $studentHousePage = StudentHousePage::query()->with(['getTitle', 'getContent'])->get();

        $dormitory = Dormitory::query()->with(['getContent'])->get();
        $dormitories = new stdClass;
        $dormitories->first_dormitory = new stdClass;
        $dormitories->second_dormitory = new stdClass;
        $dormitories->third_dormitory = new stdClass;
        foreach ($dormitory as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $dormitories->first_dormitory->costBlock = new DormitoryResource($value);
                    break;
                case 1:
                    $dormitories->first_dormitory->addressBlock = new DormitoryResource($value);
                    break;
                case 2:
                    $dormitories->first_dormitory->contactBlock = new DormitoryResource($value);
                    break;
                case 3:
                    $dormitories->second_dormitory->costBlock = new DormitoryResource($value);
                    break;
                case 4:
                    $dormitories->second_dormitory->addressBlock = new DormitoryResource($value);
                    break;
                case 5:
                    $dormitories->second_dormitory->contactBlock = new DormitoryResource($value);
                    break;
                case 6:
                    $dormitories->third_dormitory->costBlock = new DormitoryResource($value);
                    break;
                case 7:
                    $dormitories->third_dormitory->addressBlock = new DormitoryResource($value);
                    break;
                case 8:
                    $dormitories->third_dormitory->contactBlock = new DormitoryResource($value);
                    break;
            }
        }
        foreach ($studentHousePage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $aboutBlock = new PageResource ($value);
                    break;
                case 2:
                    $images[] = new PageResource ($value);
                    break;
                case 3:
                    $images[] = new PageResource ($value);
                    break;
                case 4:
                    $images[] = new PageResource ($value);
                    break;
                case 5:
                    $dormitoryButton_1 = new PageResource ($value);
                    break;
                case 6:
                    $dormitoryButton_2 = new PageResource ($value);
                    break;
                case 7:
                    $dormitoryButton_3 = new PageResource ($value);
                    break;
            }
        }
        $studentHousePageApi = new stdClass;
        $studentHousePageApi->title = $title;
        $studentHousePageApi->aboutBlock = $aboutBlock;
        $studentHousePageApi->images = $images;
        $studentHousePageApi->dormitoryButton_1 = $dormitoryButton_1;
        $studentHousePageApi->dormitory_1 = $dormitories->first_dormitory;
        $studentHousePageApi->dormitoryButton_2 = $dormitoryButton_2;
        $studentHousePageApi->dormitory_2 = $dormitories->second_dormitory;
        $studentHousePageApi->dormitoryButton_3 = $dormitoryButton_3;
        $studentHousePageApi->dormitory_3 = $dormitories->third_dormitory;
        return $studentHousePageApi;
    }

    public function travelGuidePage ()
    {
        $travelGuidePage = TravelGuidePage::query()->with(['getTitle', 'getContent'])->get();

        $documents = TravelGuidePageDocument::query()->with(['getName'])->get();
        $documentsResource = DocumentResource::collection($documents);

        foreach ($travelGuidePage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $block = new PageResource ($value);
                    break;
            }
        }
        $travelGuidePageApi = new stdClass;
        $travelGuidePageApi->title = $title;
        $travelGuidePageApi->block = $block;
        $travelGuidePageApi->documents = $documentsResource;
        
        return $travelGuidePageApi;
    }

    public function studentClubPage ()
    {
        $studentClubPage = StudentClubPage::query()->with(['getTitle', 'getContent'])->get();

        $studentClub = StudentClub::query()->with(['getDescription'])->get();
        $studentClubs = StudentClubResource::collection($studentClub);

        foreach ($studentClubPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource ($value);
                    break;
                case 1:
                    $aboutBlock = new PageResource ($value);
                    break;
                case 2:
                    $ourClubsTitle = new PageResource ($value);
                    break;
            }
        }
        $studentClubPageApi = new stdClass;
        $studentClubPageApi->title = $title;
        $studentClubPageApi->aboutBlock = $aboutBlock;
        $studentClubPageApi->ourClubsTitle = $ourClubsTitle;
        $studentClubPageApi->studentClubs = $studentClubs;
        
        return $studentClubPageApi;
    }

    public function bachelorSchool ()
    {
        $bachelorSchool = BachelorSchool::query()->with(['getName', 'getSpecialties', 'getEducators', 'getPage'])->get();
        $bachelorSchoolResource = BachelorSchoolResource::collection($bachelorSchool);
        // $studentClubPageApi->studentClubs = $studentClubs;
        
        return $bachelorSchoolResource;
    }

    public function footerNavBar (Request $request)
    {
        $lang = in_array($request->lang, ['ru', 'en', 'kz']) ? $request->lang : 'ru';
        $contactsData = $this->contacts();
        $address = explode(', ', $contactsData->address)[2];
        $contacts = [
            'id' => 6,
            'name' => $contactsData->getTabName->{$lang},
            'child' => [
                'address' => $address,
                'email' => $contactsData->admissions_committee_mail,
                'phone' => $contactsData->admissions_committee_num_2,
                'facebook' => $contactsData->facebook_link,
                'instagram' => $contactsData->instagram_link,
                'youtube' => $contactsData->youtube_link
            ]
        ];
        $navBar = $this->headerNavBar();
        $navBar = $navBar->push($contacts);
        return $navBar;
    }

    public function scienceAboutPage ()
    {
        $scienceAboutPage = ScienceAboutPage::query()->with(['getTitle', 'getContent'])->get()[0];
        return new PageResource($scienceAboutPage);
    }

    public function summerSchoolPage ()
    {
        $summerSchoolPage = SummerSchoolPage::query()->with(['getTitle', 'getContent'])->get();
        $summerSchoolProgram = SummerSchoolProgram::query()->with(['getTitle', 'getText'])->get();
        $summerSchoolProgram = SummerSchoolProgramResource::collection($summerSchoolProgram);
        $documents = SummerSchoolDocument::query()->with(['getName'])->get();
        $documents = DocumentResource::collection($documents);

        foreach ($summerSchoolPage as $key => $value)
        {
            switch ($key)
            {
                case 0:
                    $title = new PageResource($value);
                    break;
                case 1:
                    $programConceptBlock = new PageResource($value);
                    break;
                case 2:
                    $keyTopicsBlock = new PageResource($value);
                    break;
                case 3:
                    $competeciesForBlock = new PageResource($value);
                    break;
                case 4:
                    $programTitle = new PageResource($value);
                    break;
                case 5:
                    $detailsBlock[] = new PageResource($value);
                    break;
                case 6:
                    $detailsBlock[] = new PageResource($value);
                    break;
                case 7:
                    $detailsBlock[] = new PageResource($value);
                    break;
                case 8:
                    $detailsBlock[] = new PageResource($value);
                    break;
            }
        }

        $summerSchoolPageApi = new stdClass;
        $summerSchoolPageApi->title = $title;
        $summerSchoolPageApi->programConceptBlock = $programConceptBlock;
        $summerSchoolPageApi->keyTopicsBlock = $keyTopicsBlock;
        $summerSchoolPageApi->competeciesForBlock = $competeciesForBlock;
        $summerSchoolPageApi->programSchedule = new stdClass;
        $summerSchoolPageApi->programSchedule->title = $programTitle;
        $summerSchoolPageApi->programSchedule->schedule = $summerSchoolProgram;
        $summerSchoolPageApi->detailsBlock = $detailsBlock;
        $summerSchoolPageApi->documents = $documents;
        return $summerSchoolPageApi;
        // return new PageResource($scienceAboutPage);
    }
}
