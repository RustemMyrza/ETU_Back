<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@index')->name('register');


Route::post('/register', function (Request $request) {
    return app()->make(\App\Http\Controllers\Auth\RegisterController::class)->registerUser($request);
})->name('registerUser');

// Route::post('/register/{param}', [\App\Http\Controllers\Auth\RegisterController::class, 'create'])->name('registerUser');


// admin@demo.com
// Dtnthievbn2021

Auth::routes([
    'register' => false,
    'reset'    =>  false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/admin/edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('edit');
Route::post('/admin/save', [App\Http\Controllers\HomeController::class, 'save'])->name('save');
Route::resource('admin/navbar', 'App\Http\Controllers\HeaderNavBarController');
Route::resource('admin/university', 'App\Http\Controllers\AboutUniversityPageController');
Route::resource('admin/enrollment', 'App\Http\Controllers\EnrollmentPageController');
Route::resource('admin/students', 'App\Http\Controllers\StudentsPageController');
Route::resource('admin/schools', 'App\Http\Controllers\SchoolsPageController');
Route::resource('admin/science', 'App\Http\Controllers\SciencePageController');
Route::resource('admin/contacts', 'App\Http\Controllers\ContactController');
Route::resource('admin/mainPage', 'App\Http\Controllers\MainPageController');
Route::resource('admin/aboutUs', 'App\Http\Controllers\AboutUsPageController');
Route::resource('admin/news', 'App\Http\Controllers\NewsController');
Route::resource('admin/authority', 'App\Http\Controllers\AuthorityPageController');
Route::resource('admin/supervisor', 'App\Http\Controllers\SupervisorController');
Route::resource('admin/accreditation', 'App\Http\Controllers\AccreditationController');
Route::resource('admin/specialty', 'App\Http\Controllers\SpecialtyController');
Route::resource('admin/partnersPage', 'App\Http\Controllers\PartnersPageController');
Route::resource('admin/partner', 'App\Http\Controllers\PartnerController');
Route::resource('admin/careerPage', 'App\Http\Controllers\CareerPageController');
Route::resource('admin/vacancy', 'App\Http\Controllers\VacancyController');
Route::resource('admin/rectorsBlogPage', 'App\Http\Controllers\RectorsBlogPageController');
Route::resource('admin/rectorsBlogQuestion', 'App\Http\Controllers\RectorsBlogQuestionController');
Route::post('admin/rectorsBlogQuestion/create', 'App\Http\Controllers\RectorsBlogQuestionController@store')->name('rectors.question.store');
Route::resource('admin/academicCouncilPage', 'App\Http\Controllers\AcademicCouncilPageController');
Route::resource('admin/academicCouncilMember', 'App\Http\Controllers\AcademicCouncilMemberController');
Route::resource('admin/scienceInnovationPage', 'App\Http\Controllers\ScienceInnovationPageController');
Route::resource('admin/studentScience', 'App\Http\Controllers\StudentScienceController');
Route::resource('admin/discount', 'App\Http\Controllers\DiscountController');
Route::resource('admin/honorsStudentDiscount', 'App\Http\Controllers\HonorsStudentDiscountController');
Route::resource('admin/cost', 'App\Http\Controllers\CostController');
Route::resource('admin/mastersSpecialty', 'App\Http\Controllers\MastersSpecialtyController');
Route::resource('admin/languageCoursesPage', 'App\Http\Controllers\LanguageCoursesPageController');
Route::resource('admin/majorMinorPage', 'App\Http\Controllers\MajorMinorPageController');
Route::resource('admin/levelUpPage', 'App\Http\Controllers\LevelUpPageController');
Route::resource('admin/olympicsPage', 'App\Http\Controllers\OlympicsPageController');
Route::resource('admin/internationalStudentsPage', 'App\Http\Controllers\InternationalStudentsPageController');
Route::resource('admin/lincolnUniversityPage', 'App\Http\Controllers\LincolnUniversityPageController');
Route::resource('admin/academicPolicyPage', 'App\Http\Controllers\AcademicPolicyPageController');
Route::resource('admin/academicCalendarPage', 'App\Http\Controllers\AcademicCalendarPageController');
Route::resource('admin/libraryPage', 'App\Http\Controllers\LibraryPageController');
Route::resource('admin/ethicsCodePage', 'App\Http\Controllers\EthicsCodePageController');
Route::resource('admin/careerCenterPage', 'App\Http\Controllers\CareerCenterPageController');
Route::resource('admin/militaryDepartmentPage', 'App\Http\Controllers\MilitaryDepartmentPageController');
Route::resource('admin/medicalCarePage', 'App\Http\Controllers\MedicalCarePageController');
Route::resource('admin/studentHousePage', 'App\Http\Controllers\StudentHousePageController');
Route::resource('admin/dormitory', 'App\Http\Controllers\DormitoryController');
Route::resource('admin/travelGuidePage', 'App\Http\Controllers\TravelGuidePageController');
Route::resource('admin/studentClubPage', 'App\Http\Controllers\StudentClubPageController');

Route::get('admin/news/{newsId}/content', 'App\Http\Controllers\NewsContentController@index')->name('news.content.index');
Route::get('admin/news/{newsId}/content/create', 'App\Http\Controllers\NewsContentController@create')->name('news.content.create');
Route::post('admin/news/{newsId}/content/create', 'App\Http\Controllers\NewsContentController@store')->name('news.content.store');
Route::get('admin/news/{newsId}/content/{id}', 'App\Http\Controllers\NewsContentController@show')->name('news.content.show');
Route::get('admin/news/{newsId}/content/{id}/edit', 'App\Http\Controllers\NewsContentController@edit')->name('news.content.edit');
Route::delete('admin/news/{newsId}/content/{id}/delete', 'App\Http\Controllers\NewsContentController@destroy')->name('news.content.destroy');
Route::resource('admin/news/{newsId}/content', 'App\Http\Controllers\NewsContentController')->except(['index', 'show']);

Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page', 'App\Http\Controllers\MastersSpecialtyPageController@index')->name('mastersSpecialty.page.index');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page/create', 'App\Http\Controllers\MastersSpecialtyPageController@create')->name('mastersSpecialty.page.create');
Route::post('admin/mastersSpecialty/{mastersSpecialtyId}/page/create', 'App\Http\Controllers\MastersSpecialtyPageController@store')->name('mastersSpecialty.page.store');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page/{id}', 'App\Http\Controllers\MastersSpecialtyPageController@show')->name('mastersSpecialty.page.show');
Route::get('admin/mastersSpecialty/{mastersSpecialtyId}/page/{id}/edit', 'App\Http\Controllers\MastersSpecialtyPageController@edit')->name('mastersSpecialty.page.edit');
Route::delete('admin/mastersSpecialty/{mastersSpecialtyId}/page/{id}/delete', 'App\Http\Controllers\MastersSpecialtyPageController@destroy')->name('mastersSpecialty.page.destroy');
Route::resource('admin/mastersSpecialty/{mastersSpecialtyId}/page', 'App\Http\Controllers\MastersSpecialtyPageController')->except(['index', 'show']);

Route::get('admin/vacancy/{vacancyId}/applications', 'App\Http\Controllers\VacancyApplicationController@index')->name('vacancy.application.index');
// Route::get('admin/vacancy/{vacancyId}/applications/create', 'App\Http\Controllers\VacancyApplicationController@create')->name('vacancy.application.create');
Route::post('admin/vacancy/{vacancyId}/applications/create', 'App\Http\Controllers\VacancyApplicationController@store')->name('vacancy.application.store');
Route::get('admin/vacancy/{vacancyId}/applications/{id}', 'App\Http\Controllers\VacancyApplicationController@show')->name('vacancy.application.show');
Route::delete('admin/vacancy/{vacancyId}/applications/{id}/delete', 'App\Http\Controllers\VacancyApplicationController@destroy')->name('vacancy.application.destroy');