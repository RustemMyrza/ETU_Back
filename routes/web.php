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
Route::resource('admin/academicCouncilPage', 'App\Http\Controllers\AcademicCouncilPageController');
Route::resource('admin/academicCouncilMember', 'App\Http\Controllers\AcademicCouncilMemberController');

Route::get('admin/news/{newsId}/content', 'App\Http\Controllers\NewsContentController@index')->name('news.content.index');
Route::get('admin/news/{newsId}/content/create', 'App\Http\Controllers\NewsContentController@create')->name('news.content.create');
Route::post('admin/news/{newsId}/content/create', 'App\Http\Controllers\NewsContentController@store')->name('news.content.store');
Route::get('admin/news/{newsId}/content/{id}', 'App\Http\Controllers\NewsContentController@show')->name('news.content.show');
Route::get('admin/news/{newsId}/content/{id}/edit', 'App\Http\Controllers\NewsContentController@edit')->name('news.content.edit');
Route::delete('admin/news/{newsId}/content/{id}/delete', 'App\Http\Controllers\NewsContentController@destroy')->name('news.content.destroy');
Route::resource('admin/news/{newsId}/content', 'App\Http\Controllers\NewsContentController')->except(['index', 'show']);

Route::get('admin/vacancy/{vacancyId}/applications', 'App\Http\Controllers\VacancyApplicationController@index')->name('vacancy.application.index');
// Route::get('admin/vacancy/{vacancyId}/applications/create', 'App\Http\Controllers\VacancyApplicationController@create')->name('vacancy.application.create');
Route::post('admin/vacancy/{vacancyId}/applications/create', 'App\Http\Controllers\VacancyApplicationController@store')->name('vacancy.application.store');
Route::get('admin/vacancy/{vacancyId}/applications/{id}', 'App\Http\Controllers\VacancyApplicationController@show')->name('vacancy.application.show');
Route::delete('admin/vacancy/{vacancyId}/applications/{id}/delete', 'App\Http\Controllers\VacancyApplicationController@destroy')->name('vacancy.application.destroy');