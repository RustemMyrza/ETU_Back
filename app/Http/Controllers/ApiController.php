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
use App\Models\Discount;
use App\Models\HonorsStudentDiscount;
use App\Models\Cost;
use App\Models\SummerSchoolSlider;
use App\Models\YoutubeVideo;
use App\Models\InstagramLink;
use App\Models\InstagramImage;
use App\Models\MainPageMeta;
use App\Models\AboutUniversityPagesMeta;
use App\Models\EnrollmentPagesMeta;
use App\Models\StudentsPagesMeta;
use App\Models\BachelorSchoolMeta;
use App\Models\LibraryPageDocument;
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
use App\Http\Resources\DiscountTableResource;
use App\Http\Resources\CostTableResource;
use App\Http\Resources\HonorsStudentDiscountTableResource;
use App\Http\Resources\InstagramImageResource;
use App\Http\Resources\MetaDataResource;
use App\Models\AboutUniversityPage;
use App\Models\MetaData;
use App\Models\SciencePagesMeta;
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
        $youtubeLink = YoutubeVideo::first() ? YoutubeVideo::first()->link : null;
        $instagramLink = InstagramLink::first() ? InstagramLink::first()->link : null;
        $instagramImages = InstagramImage::query()->get() ? InstagramImage::query()->get() : null;
        $instagramImages = InstagramImageResource::collection($instagramImages);
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
                    $instagramTitle = new MainPageResource($value);
                    break;
                case 18:
                    $youtubeTitle = new MainPageResource($value);
                    break;
                case 19:
                    $applicationTitle = new MainPageResource($value);
                    break;
                case 20:
                    $applicationDescription = new MainPageResource($value);
                    break;
                case 21:
                    $applicationButton = new MainPageResource($value);
                    break;
                case 22:
                    $faqTitle = new MainPageResource($value);
                    break;
                case 23:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 24:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 25:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 26:
                    $faqQuestions[] = new MainPageResource($value);
                    break;
                case 27:
                    $faqQuestions[] = new MainPageResource($value);
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
        $mainPageApi->youtube = new stdClass;
        $mainPageApi->youtube->title = $youtubeTitle;
        $mainPageApi->youtube->link =  $youtubeLink;
        $mainPageApi->instagram = new stdClass;
        $mainPageApi->instagram->title = $instagramTitle;
        $mainPageApi->instagram->link = $instagramLink;
        $mainPageApi->instagram->images = $instagramImages;
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
        $metaData = AboutUniversityPagesMeta::where('page_id', 1)->first();
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
        $aboutUsApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $aboutUsApi;
    }

    public function authority ()
    {
        $authority = AuthorityPage::query()->with(['getTitle', 'getContent'])->get();
        $supervisor = Supervisor::query()->with(['getName', 'getPosition', 'getAddress'])->get();
        $metaData = AboutUniversityPagesMeta::where('page_id', 2)->first();
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
        $authorityApi->supervisors = isset($supervisors) ? $supervisors : [];
        $authorityApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $authorityApi;
    }

    public function accreditation (Request $request)
    {
        $accreditation = Accreditation::query()->with(['getTitle', 'getContent'])->get();
        $metaData = AboutUniversityPagesMeta::where('page_id', 3)->first();
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
        $accreditationApi->specialties = isset($specialties) ? $specialties : [];
        $accreditationApi->button = $button;
        $accreditationApi->documents = $resourceDocuments;
        $accreditationApi->documentsDownloadButton = $documentsDownloadButton;
        $accreditationApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $accreditationApi;
    }

    public function partnersPage ()
    {
        $partnersPage = PartnersPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = AboutUniversityPagesMeta::where('page_id', 4)->first();
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
        $partnersPageApi->partners = isset($partners) ? $partners : [];
        $partnersPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
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
                    'image' => url($item->image),
                    'type' => $item->type
                ];
            }
            else
            {
                $partnersByType['aboutInternationalPartnersItems'][] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => url($item->image),
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
        $metaData = AboutUniversityPagesMeta::where('page_id', 6)->first();
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
        $careerPageApi->vacancies = isset($vacancies) ? $vacancies : [];
        $careerPageApi->button = $button;
        $careerPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $careerPageApi;
    }

    public function rectorsBlogPage ()
    {
        $rectorsBlogPage = RectorsBlogPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = AboutUniversityPagesMeta::where('page_id', 5)->first();
        $questions = RectorsBlogQuestion::query()->latest()->paginate(5);
        
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
        $rectorsBlogPageApi->meta = $metaData ? new MetaDataResource($metaData) : null; 
        return $rectorsBlogPageApi;
    }

    public function academicCouncilPage ()
    {
        $academicCouncilPage = AcademicCouncilPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = AboutUniversityPagesMeta::where('page_id', 8)->first();
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
        $academicCouncilPageApi->members = isset($academicCouncilMembers) ? $academicCouncilMembers : [];
        $academicCouncilPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
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
        $documents = AdmissionsCommitteePageDocument::query()->with(['getName'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 1)->first();
        $documents = DocumentResource::collection($documents);
        $discountTable = Discount::query()->with(['getCategory', 'getNote'])->get();
        foreach ($discountTable as $item)
        {
            if ($item->student_type == 1)
            {
                $discountTable_1[] = new DiscountTableResource($item);
            }
            else
            {
                $discountTable_2[] = new DiscountTableResource($item);
            }
        }
        $honorsStudentDiscountTable = HonorsStudentDiscount::query()->with(['getCategory', 'getNote'])->get();
        
        $honorsStudentDiscountTableResource = HonorsStudentDiscountTableResource::collection($honorsStudentDiscountTable);
        $costTable = Cost::query()->with(['getProgram'])->get();

        foreach ($costTable as $item)
        {
            if ($item->type == 1)
            {
                $costTable_1[] = new CostTableResource($item);
            }
            else if ($item->type == 2)
            {
                $costTable_2[] = new CostTableResource($item);
            }
            else
            {
                $costTable_3[] = new CostTableResource($item);
            }
        }
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
                    $tableTitle_6 = new PageResource($value);
                    break;
                case 9:
                    $listOfDocumentsTitle = new PageResource($value);
                    break;
                case 10:
                    $bachelorBlock = new PageResource($value);
                    break;
                case 11:
                    $masterBlock = new PageResource($value);
                    break;
            }
        }
        $admissionsCommitteePageApi = new stdClass;
        $admissionsCommitteePageApi->admissionsCommitteeTitle = $admissionsCommitteeTitle;
        $admissionsCommitteePageApi->documents = isset($documents) ? $documents : [];
        $admissionsCommitteePageApi->discountsTitle = $discountsTitle;
        $admissionsCommitteePageApi->tableTitle_1 = $tableTitle_1;
        $admissionsCommitteePageApi->discountTable_1 = isset($discountTable_1) ? $discountTable_1 : [];
        $admissionsCommitteePageApi->tableTitle_2 = $tableTitle_2;
        $admissionsCommitteePageApi->discountTable_2 = isset($discountTable_2) ? $discountTable_2 : [];
        $admissionsCommitteePageApi->tableTitle_3 = $tableTitle_3;
        $admissionsCommitteePageApi->discountTable_3 = isset($honorsStudentDiscountTableResource) ? $honorsStudentDiscountTableResource : [];
        $admissionsCommitteePageApi->costTitle = $costTitle;
        $admissionsCommitteePageApi->tableTitle_4 = $tableTitle_4;
        $admissionsCommitteePageApi->costTable_1 = isset($costTable_1) ? $costTable_1 : [];
        $admissionsCommitteePageApi->tableTitle_5 = $tableTitle_5;
        $admissionsCommitteePageApi->costTable_2 = isset($costTable_2) ? $costTable_2 : [];
        $admissionsCommitteePageApi->tableTitle_6 = $tableTitle_6;
        $admissionsCommitteePageApi->costTable_3 = isset($costTable_3) ? $costTable_3 : [];
        $admissionsCommitteePageApi->listOfDocumentsTitle = $listOfDocumentsTitle;
        $admissionsCommitteePageApi->bachelorBlock = $bachelorBlock;
        $admissionsCommitteePageApi->masterBlock = $masterBlock;
        $admissionsCommitteePageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $admissionsCommitteePageApi;
    }

    public function masterPage ()
    {
        $masterPage = MasterPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 3)->first();
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
        $masterPageApi->specalties = isset($masterSpecialties) ? $masterSpecialties : [];
        $masterPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $masterPageApi;
    }

    public function internationalStudentsPage ()
    {
        $internationalStudentsPage = InternationalStudentsPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 4)->first();
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
        $internationalStudentsPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $internationalStudentsPageApi;
    }

    public function languageCoursesPage ()
    {
        $languageCoursesPage = LanguageCoursesPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 5)->first();
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
        $languageCoursesPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $languageCoursesPageApi;
    }

    public function majorMinorPage ()
    {
        $majorMinorPage = MajorMinorPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 6)->first();
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
        $majorMinorPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $majorMinorPageApi;
    }

    public function levelUpPage ()
    {
        $levelUpPage = LevelUpPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 7)->first();
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
        $levelUpPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $levelUpPageApi;
    }

    public function olympicsPage ()
    {
        $olympicsPage = OlympicsPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 8)->first();
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
        $olympicsPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
        return $olympicsPageApi;
    }

    public function lincolnUniversityPage ()
    {
        $lincolnUniversityPage = LincolnUniversityPage::query()->with(['getTitle', 'getContent'])->get();
        $metaData = EnrollmentPagesMeta::where('page_id', 9)->first();
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
        $lincolnUniversityPageApi->meta = $metaData ? new MetaDataResource($metaData) : null;
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
        $document = LibraryPageDocument::query()->with(['getName'])->first();
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
        $libraryPageApi->document = $document ? new DocumentResource($document) : null;
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
        $studentHousePageApi->dormitoryButton_1 = isset($dormitoryButton_1) ? $dormitoryButton_1 : null;
        $studentHousePageApi->dormitory_1 = isset($dormitories->first_dormitory) ? $dormitories->first_dormitory : [];
        $studentHousePageApi->dormitoryButton_2 = isset($dormitoryButton_2) ? $dormitoryButton_2 : null;
        $studentHousePageApi->dormitory_2 = isset($dormitories->second_dormitory) ? $dormitories->second_dormitory : [];
        $studentHousePageApi->dormitoryButton_3 = isset($dormitoryButton_3) ? $dormitoryButton_3 : null;
        $studentHousePageApi->dormitory_3 = isset($dormitories->third_dormitory) ? $dormitories->third_dormitory : [];
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
        $studentClubPageApi->studentClubs = isset($studentClubs) ? $studentClubs : [];
        
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
        $footerNavBar = new stdClass;
        $footerNavBar->navbar = $navBar;
        $footerNavBar->contacts = $contacts;
        return $footerNavBar;
    }

    public function scienceAboutPage ()
    {
        $scienceAboutPage = ScienceAboutPage::query()->with(['getTitle', 'getContent'])->get()[0];
        $metaData = AboutUniversityPagesMeta::where('page_id', 7)->first();

        return new PageResource($scienceAboutPage);
    }

    public function summerSchoolPage ()
    {
        $summerSchoolPage = SummerSchoolPage::query()->with(['getTitle', 'getContent'])->get();
        $summerSchoolProgram = SummerSchoolProgram::query()->with(['getTitle', 'getText'])->get();
        $summerSchoolProgram = SummerSchoolProgramResource::collection($summerSchoolProgram);
        $slider = SummerSchoolSlider::first();
        if (isset($slider->images))
        {
            foreach(json_decode($slider->images) as $item)
            {
                $sliderImages[] = url($item);
            }
        }
        // return $sliderImages;
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
        $summerSchoolPageApi->slider = isset($sliderImages) ? $sliderImages : null;
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

    public function metaData()
    {
        $mainPage = MainPageMeta::first();
        $pages = [
            'aboutUs' => ['model' => AboutUniversityPagesMeta::class, 'id' => 1],
            'authority' => ['model' => AboutUniversityPagesMeta::class, 'id' => 2],
            'accreditation' => ['model' => AboutUniversityPagesMeta::class, 'id' => 3],
            'ourPartners' => ['model' => AboutUniversityPagesMeta::class, 'id' => 4],
            'rectorsBlog' => ['model' => AboutUniversityPagesMeta::class, 'id' => 5],
            'career' => ['model' => AboutUniversityPagesMeta::class, 'id' => 6],
            'scienceAbout' => ['model' => AboutUniversityPagesMeta::class, 'id' => 7],
            'academicCouncil' => ['model' => AboutUniversityPagesMeta::class, 'id' => 8],
            'admissionsCommittee' => ['model' => EnrollmentPagesMeta::class, 'id' => 1],
            'bachelor' => ['model' => EnrollmentPagesMeta::class, 'id' => 2],
            'master' => ['model' => EnrollmentPagesMeta::class, 'id' => 3],
            'internationalStudents' => ['model' => EnrollmentPagesMeta::class, 'id' => 4],
            'languageCourse' => ['model' => EnrollmentPagesMeta::class, 'id' => 5],
            'majorMinor' => ['model' => EnrollmentPagesMeta::class, 'id' => 6],
            'levelUp' => ['model' => EnrollmentPagesMeta::class, 'id' => 7],
            'olympics' => ['model' => EnrollmentPagesMeta::class, 'id' => 8],
            'lincolnUniversity' => ['model' => EnrollmentPagesMeta::class, 'id' => 9],
            'academicPolicy' => ['model' => StudentsPagesMeta::class, 'id' => 1],
            'academicCalendar' => ['model' => StudentsPagesMeta::class, 'id' => 2],
            'library' => ['model' => StudentsPagesMeta::class, 'id' => 3],
            'ethicalCode' => ['model' => StudentsPagesMeta::class, 'id' => 4],
            'careerCenter' => ['model' => StudentsPagesMeta::class, 'id' => 5],
            'militaryDepartment' => ['model' => StudentsPagesMeta::class, 'id' => 6],
            'medicalCare' => ['model' => StudentsPagesMeta::class, 'id' => 7],
            'studentHouse' => ['model' => StudentsPagesMeta::class, 'id' => 8],
            'travelGuide' => ['model' => StudentsPagesMeta::class, 'id' => 9],
            'studentOrganization' => ['model' => StudentsPagesMeta::class, 'id' => 10],
            'bachelorSchools_1' => ['model' => BachelorSchoolMeta::class, 'id' => 1],
            'bachelorSchools_2' => ['model' => BachelorSchoolMeta::class, 'id' => 2],
            'bachelorSchools_3' => ['model' => BachelorSchoolMeta::class, 'id' => 3],
            'bachelorSchools_4' => ['model' => BachelorSchoolMeta::class, 'id' => 4],
            'scienceInnovation' => ['model' => SciencePagesMeta::class, 'id' => 1],
            'scientificPublication' => ['model' => SciencePagesMeta::class, 'id' => 2],
            'studentScience' => ['model' => SciencePagesMeta::class, 'id' => 3],
            'summerSchool' => ['model' => SciencePagesMeta::class, 'id' => 4],
        ];

        $metaData = new stdClass;
        $metaData->mainPage = $mainPage ? new MetaDataResource($mainPage) : null;
        foreach ($pages as $key => $page) {
            $pageData = $page['model']::where('page_id', $page['id'])->first();
            $metaData->$key = $pageData ? new MetaDataResource($pageData) : null;
        }

        return $metaData;
}

}
