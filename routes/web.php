<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Validator;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Auth::routes([
    'register' => false,
    'reset'    =>  false,
]);
Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@index')->name('register');


Route::post('/register', function (Request $request) {
    return app()->make(\App\Http\Controllers\Auth\RegisterController::class)->registerUser($request);
})->name('registerUser');

// Route::post('/register/{param}', [\App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('registerUser');


// admin@demo.com
// Dtnthievbn2021


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin/edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('edit')->middleware('auth');
Route::post('/admin/save', [App\Http\Controllers\HomeController::class, 'save'])->name('save')->middleware('auth');
Route::resource('admin/navbar', 'App\Http\Controllers\HeaderNavBarController')->middleware('auth');
Route::resource('admin/university', 'App\Http\Controllers\AboutUniversityPageController')->middleware('auth');
Route::resource('admin/enrollment', 'App\Http\Controllers\EnrollmentPageController')->middleware('auth');
Route::resource('admin/students', 'App\Http\Controllers\StudentsPageController')->middleware('auth');
Route::resource('admin/schools', 'App\Http\Controllers\SchoolsPageController')->middleware('auth');
Route::resource('admin/science', 'App\Http\Controllers\SciencePageController')->middleware('auth');
Route::resource('admin/contacts', 'App\Http\Controllers\ContactController')->middleware('auth');
Route::resource('admin/mainPage', 'App\Http\Controllers\MainPageController')->middleware('auth');
Route::resource('admin/aboutUs', 'App\Http\Controllers\AboutUsPageController')->middleware('auth');
Route::resource('admin/news', 'App\Http\Controllers\NewsController')->middleware('auth');
Route::resource('admin/authority', 'App\Http\Controllers\AuthorityPageController')->middleware('auth');
Route::resource('admin/supervisor', 'App\Http\Controllers\SupervisorController')->middleware('auth');
Route::resource('admin/accreditation', 'App\Http\Controllers\AccreditationController')->middleware('auth');
Route::resource('admin/specialty', 'App\Http\Controllers\SpecialtyController')->middleware('auth');
Route::resource('admin/partnersPage', 'App\Http\Controllers\PartnersPageController')->middleware('auth');
Route::resource('admin/partner', 'App\Http\Controllers\PartnerController')->middleware('auth');
Route::resource('admin/careerPage', 'App\Http\Controllers\CareerPageController')->middleware('auth');
Route::resource('admin/vacancy', 'App\Http\Controllers\VacancyController')->middleware('auth');
Route::resource('admin/rectorsBlogPage', 'App\Http\Controllers\RectorsBlogPageController')->middleware('auth');
Route::resource('admin/rectorsBlogQuestion', 'App\Http\Controllers\RectorsBlogQuestionController')->middleware('auth');
Route::post('admin/rectorsBlogQuestion/create', 'App\Http\Controllers\RectorsBlogQuestionController@store')->name('rectors.question.store')->middleware('auth');
Route::resource('admin/academicCouncilPage', 'App\Http\Controllers\AcademicCouncilPageController')->middleware('auth');
Route::resource('admin/academicCouncilMember', 'App\Http\Controllers\AcademicCouncilMemberController')->middleware('auth');
Route::resource('admin/scienceInnovationPage', 'App\Http\Controllers\ScienceInnovationPageController')->middleware('auth');
Route::resource('admin/studentScience', 'App\Http\Controllers\StudentScienceController')->middleware('auth');
Route::resource('admin/discount', 'App\Http\Controllers\DiscountController')->middleware('auth');
Route::resource('admin/honorsStudentDiscount', 'App\Http\Controllers\HonorsStudentDiscountController')->middleware('auth');
Route::resource('admin/cost', 'App\Http\Controllers\CostController')->middleware('auth');
Route::resource('admin/mastersSpecialty', 'App\Http\Controllers\MastersSpecialtyController')->middleware('auth');
Route::resource('admin/languageCoursesPage', 'App\Http\Controllers\LanguageCoursesPageController')->middleware('auth');
Route::resource('admin/majorMinorPage', 'App\Http\Controllers\MajorMinorPageController')->middleware('auth');
Route::resource('admin/levelUpPage', 'App\Http\Controllers\LevelUpPageController')->middleware('auth');
Route::resource('admin/olympicsPage', 'App\Http\Controllers\OlympicsPageController')->middleware('auth');
Route::resource('admin/internationalStudentsPage', 'App\Http\Controllers\InternationalStudentsPageController')->middleware('auth');
Route::resource('admin/lincolnUniversityPage', 'App\Http\Controllers\LincolnUniversityPageController')->middleware('auth');
Route::resource('admin/academicPolicyPage', 'App\Http\Controllers\AcademicPolicyPageController')->middleware('auth');
Route::resource('admin/academicCalendarPage', 'App\Http\Controllers\AcademicCalendarPageController')->middleware('auth');
Route::resource('admin/libraryPage', 'App\Http\Controllers\LibraryPageController')->middleware('auth');
Route::resource('admin/ethicsCodePage', 'App\Http\Controllers\EthicsCodePageController')->middleware('auth');
Route::resource('admin/careerCenterPage', 'App\Http\Controllers\CareerCenterPageController')->middleware('auth');
Route::resource('admin/militaryDepartmentPage', 'App\Http\Controllers\MilitaryDepartmentPageController')->middleware('auth');
Route::resource('admin/medicalCarePage', 'App\Http\Controllers\MedicalCarePageController')->middleware('auth');
Route::resource('admin/studentHousePage', 'App\Http\Controllers\StudentHousePageController')->middleware('auth');
Route::resource('admin/dormitory', 'App\Http\Controllers\DormitoryController')->middleware('auth');
Route::resource('admin/travelGuidePage', 'App\Http\Controllers\TravelGuidePageController')->middleware('auth');
Route::resource('admin/studentClubPage', 'App\Http\Controllers\StudentClubPageController')->middleware('auth');
Route::resource('admin/scienceAboutPage', 'App\Http\Controllers\ScienceAboutPageController')->middleware('auth');
Route::resource('admin/admissionsCommitteePage', 'App\Http\Controllers\AdmissionsCommitteePageController')->middleware('auth');
Route::resource('admin/studentClub', 'App\Http\Controllers\StudentClubController')->middleware('auth');
Route::resource('admin/masterPage', 'App\Http\Controllers\MasterPageController')->middleware('auth');
Route::resource('admin/scientificPublicationPage', 'App\Http\Controllers\ScientificPublicationPageController')->middleware('auth');
Route::resource('admin/bachelorSchool', 'App\Http\Controllers\BachelorSchoolController')->middleware('auth');
Route::resource('admin/scienceInnovationPageDocument', 'App\Http\Controllers\ScienceInnovationPageDocumentController')->middleware('auth');
Route::resource('admin/accreditationPageDocument', 'App\Http\Controllers\AccreditationPageDocumentController')->middleware('auth');
Route::resource('admin/academicCalendarPageDocument', 'App\Http\Controllers\AcademicCalendarPageDocumentController')->middleware('auth');
Route::resource('admin/academicPolicyPageDocument', 'App\Http\Controllers\AcademicPolicyPageDocumentController')->middleware('auth');
Route::resource('admin/ethicalCodePageDocument', 'App\Http\Controllers\EthicalCodePageDocumentController')->middleware('auth');
Route::resource('admin/internationalStudentsDocument', 'App\Http\Controllers\InternationalStudentsPageDocumentController')->middleware('auth');
Route::resource('admin/lincolnUniversityPageDocument', 'App\Http\Controllers\LincolnUniversityPageDocumentController')->middleware('auth');
Route::resource('admin/olympicsPageDocument', 'App\Http\Controllers\OlympicsPageDocumentController')->middleware('auth');
Route::resource('admin/scientificPublicationDocument', 'App\Http\Controllers\ScientificPublicationController')->middleware('auth');
Route::resource('admin/studentSciencePageDocument', 'App\Http\Controllers\StudentSciencePageDocumentController')->middleware('auth');
Route::resource('admin/travelGuidePageDocument', 'App\Http\Controllers\TravelGuidePageDocumentController')->middleware('auth');
Route::resource('admin/scienceInnovationPageDocument', 'App\Http\Controllers\ScienceInnovationPageDocumentController')->middleware('auth');
Route::resource('admin/admissionsCommitteePageDocument', 'App\Http\Controllers\AdmissionsCommitteePageDocumentController')->middleware('auth');
Route::resource('admin/summerSchoolPage', 'App\Http\Controllers\SummerSchoolPageController')->middleware('auth');
Route::resource('admin/summerSchoolProgram', 'App\Http\Controllers\SummerSchoolProgramController')->middleware('auth');
Route::resource('admin/summerSchoolDocument', 'App\Http\Controllers\SummerSchoolDocumentController')->middleware('auth');

Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController@index')->middleware('auth')->name('specialty.page.index');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page/create', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController@create')->middleware('auth')->name('specialty.page.create');
Route::post('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page/create', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController@store')->middleware('auth')->name('specialty.page.store');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page/{id}', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController@show')->middleware('auth')->name('specialty.page.show');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page/{id}/edit', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController@edit')->middleware('auth')->name('specialty.page.edit');
Route::delete('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page/{id}/delete', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController@destroy')->middleware('auth')->name('specialty.page.destroy');
Route::resource('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/page', 'App\Http\Controllers\BachelorSchoolSpecialtyPageController')->middleware('auth')->except(['index', 'show']);

Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents', 'App\Http\Controllers\BachelorSpecialtyDocumentController@index')->middleware('auth')->name('bachelorSpecialty.document.index');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents/create', 'App\Http\Controllers\BachelorSpecialtyDocumentController@create')->middleware('auth')->name('bachelorSpecialty.document.create');
Route::post('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents/create', 'App\Http\Controllers\BachelorSpecialtyDocumentController@store')->middleware('auth')->name('bachelorSpecialty.document.store');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents/{id}', 'App\Http\Controllers\BachelorSpecialtyDocumentController@show')->middleware('auth')->name('bachelorSpecialty.document.show');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents/{id}/edit', 'App\Http\Controllers\BachelorSpecialtyDocumentController@edit')->middleware('auth')->name('bachelorSpecialty.document.edit');
Route::delete('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents/{id}/delete', 'App\Http\Controllers\BachelorSpecialtyDocumentController@destroy')->middleware('auth')->name('bachelorSpecialty.document.destroy');
Route::resource('admin/bachelorSchool/{schoolId}/specialty/{specialtyId}/documents', 'App\Http\Controllers\BachelorSpecialtyDocumentController')->middleware('auth')->except(['index', 'show']);



Route::get('admin/bachelorSchool/{schoolId}/page', 'App\Http\Controllers\BachelorSchoolPageController@index')->middleware('auth')->name('school.page.index');
Route::get('admin/bachelorSchool/{schoolId}/page/create', 'App\Http\Controllers\BachelorSchoolPageController@create')->middleware('auth')->name('school.page.create');
Route::post('admin/bachelorSchool/{schoolId}/page/create', 'App\Http\Controllers\BachelorSchoolPageController@store')->middleware('auth')->name('school.page.store');
Route::get('admin/bachelorSchool/{schoolId}/page/{id}', 'App\Http\Controllers\BachelorSchoolPageController@show')->middleware('auth')->name('school.page.show');
Route::get('admin/bachelorSchool/{schoolId}/page/{id}/edit', 'App\Http\Controllers\BachelorSchoolPageController@edit')->middleware('auth')->name('school.page.edit');
Route::delete('admin/bachelorSchool/{schoolId}/page/{id}/delete', 'App\Http\Controllers\BachelorSchoolPageController@destroy')->middleware('auth')->name('school.page.destroy');
Route::resource('admin/bachelorSchool/{schoolId}/page', 'App\Http\Controllers\BachelorSchoolPageController')->middleware('auth')->except(['index', 'show']);

Route::get('admin/bachelorSchool/{schoolId}/specialty', 'App\Http\Controllers\BachelorSchoolSpecialtyController@index')->middleware('auth')->name('school.specialty.index');
Route::get('admin/bachelorSchool/{schoolId}/specialty/create', 'App\Http\Controllers\BachelorSchoolSpecialtyController@create')->middleware('auth')->name('school.specialty.create');
Route::post('admin/bachelorSchool/{schoolId}/specialty/create', 'App\Http\Controllers\BachelorSchoolSpecialtyController@store')->middleware('auth')->name('school.specialty.store');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{id}', 'App\Http\Controllers\BachelorSchoolSpecialtyController@show')->middleware('auth')->name('school.specialty.show');
Route::get('admin/bachelorSchool/{schoolId}/specialty/{id}/edit', 'App\Http\Controllers\BachelorSchoolSpecialtyController@edit')->middleware('auth')->name('school.specialty.edit');
Route::delete('admin/bachelorSchool/{schoolId}/specialty/{id}/delete', 'App\Http\Controllers\BachelorSchoolSpecialtyController@destroy')->middleware('auth')->name('school.specialty.destroy');
Route::resource('admin/bachelorSchool/{schoolId}/specialty', 'App\Http\Controllers\BachelorSchoolSpecialtyController')->middleware('auth')->except(['index', 'show']);



Route::get('admin/bachelorSchool/{schoolId}/educator', 'App\Http\Controllers\BachelorSchoolEducatorController@index')->middleware('auth')->name('school.educator.index');
Route::get('admin/bachelorSchool/{schoolId}/educator/create', 'App\Http\Controllers\BachelorSchoolEducatorController@create')->middleware('auth')->name('school.educator.create');
Route::post('admin/bachelorSchool/{schoolId}/educator/create', 'App\Http\Controllers\BachelorSchoolEducatorController@store')->middleware('auth')->name('school.educator.store');
Route::get('admin/bachelorSchool/{schoolId}/educator/{id}', 'App\Http\Controllers\BachelorSchoolEducatorController@show')->middleware('auth')->name('school.educator.show');
Route::get('admin/bachelorSchool/{schoolId}/educator/{id}/edit', 'App\Http\Controllers\BachelorSchoolEducatorController@edit')->middleware('auth')->name('school.educator.edit');
Route::delete('admin/bachelorSchool/{schoolId}/educator/{id}/delete', 'App\Http\Controllers\BachelorSchoolEducatorController@destroy')->middleware('auth')->name('school.educator.destroy');
Route::resource('admin/bachelorSchool/{schoolId}/educator', 'App\Http\Controllers\BachelorSchoolEducatorController')->middleware('auth')->except(['index', 'show']);

Route::get('admin/news/{newsId}/content', 'App\Http\Controllers\NewsContentController@index')->middleware('auth')->name('news.content.index');
Route::get('admin/news/{newsId}/content/create', 'App\Http\Controllers\NewsContentController@create')->middleware('auth')->name('news.content.create');
Route::post('admin/news/{newsId}/content/create', 'App\Http\Controllers\NewsContentController@store')->middleware('auth')->name('news.content.store');
Route::get('admin/news/{newsId}/content/{id}', 'App\Http\Controllers\NewsContentController@show')->middleware('auth')->name('news.content.show');
Route::get('admin/news/{newsId}/content/{id}/edit', 'App\Http\Controllers\NewsContentController@edit')->middleware('auth')->name('news.content.edit');
Route::delete('admin/news/{newsId}/content/{id}/delete', 'App\Http\Controllers\NewsContentController@destroy')->middleware('auth')->name('news.content.destroy');
Route::resource('admin/news/{newsId}/content', 'App\Http\Controllers\NewsContentController')->middleware('auth')->except(['index', 'show']);

Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page', 'App\Http\Controllers\MastersSpecialtyPageController@index')->middleware('auth')->name('mastersSpecialty.page.index');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page/create', 'App\Http\Controllers\MastersSpecialtyPageController@create')->middleware('auth')->name('mastersSpecialty.page.create');
Route::post('admin/mastersSpecialty/{mastersSpecialtyId}/page/create', 'App\Http\Controllers\MastersSpecialtyPageController@store')->middleware('auth')->name('mastersSpecialty.page.store');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page/{id}', 'App\Http\Controllers\MastersSpecialtyPageController@show')->middleware('auth')->name('mastersSpecialty.page.show');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page/{id}/edit', 'App\Http\Controllers\MastersSpecialtyPageController@edit')->middleware('auth')->name('mastersSpecialty.page.edit');
Route::delete('admin/mastersSpecialty/{mastersSpecialtyId}/page/{id}/delete', 'App\Http\Controllers\MastersSpecialtyPageController@destroy')->middleware('auth')->name('mastersSpecialty.page.destroy');
Route::resource('admin/mastersSpecialty/{mastersSpecialtyId}/page', 'App\Http\Controllers\MastersSpecialtyPageController')->middleware('auth')->except(['index', 'show']);

Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/documents', 'App\Http\Controllers\MasterSpecialtyPageDocumentController@index')->middleware('auth')->name('mastersSpecialty.document.index');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/documents/create', 'App\Http\Controllers\MasterSpecialtyPageDocumentController@create')->middleware('auth')->name('mastersSpecialty.document.create');
Route::post('admin/mastersSpecialty/{mastersSpecialtyId}/documents/create', 'App\Http\Controllers\MasterSpecialtyPageDocumentController@store')->middleware('auth')->name('mastersSpecialty.document.store');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/documents/{id}', 'App\Http\Controllers\MasterSpecialtyPageDocumentController@show')->middleware('auth')->name('mastersSpecialty.document.show');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/documents/{id}/edit', 'App\Http\Controllers\MasterSpecialtyPageDocumentController@edit')->middleware('auth')->name('mastersSpecialty.document.edit');
Route::delete('admin/mastersSpecialty/{mastersSpecialtyId}/documents/{id}/delete', 'App\Http\Controllers\MasterSpecialtyPageDocumentController@destroy')->middleware('auth')->name('mastersSpecialty.document.destroy');
Route::resource('admin/mastersSpecialty/{mastersSpecialtyId}/documents', 'App\Http\Controllers\MasterSpecialtyPageDocumentController')->middleware('auth')->except(['index', 'show']);

Route::get('admin/vacancy/{vacancyId}/applications', 'App\Http\Controllers\VacancyApplicationController@index')->middleware('auth')->name('vacancy.application.index');
// Route::get('admin/vacancy/{vacancyId}/applications/create', 'App\Http\Controllers\VacancyApplicationController@create')->name('vacancy.application.create');
Route::post('admin/vacancy/{vacancyId}/applications/create', 'App\Http\Controllers\VacancyApplicationController@store')->middleware('auth')->name('vacancy.application.store');
Route::get('admin/vacancy/{vacancyId}/applications/{id}', 'App\Http\Controllers\VacancyApplicationController@show')->middleware('auth')->name('vacancy.application.show');
Route::delete('admin/vacancy/{vacancyId}/applications/{id}/delete', 'App\Http\Controllers\VacancyApplicationController@destroy')->middleware('auth')->name('vacancy.application.destroy');