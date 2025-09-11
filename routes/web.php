<?php

use App\Models\Grievance\ComplaintDetail;


use App\Http\Controllers\Frontend\Admin\HomeController;
use App\Http\Controllers\Frontend\Admin\AdminController;
use App\Http\Controllers\Frontend\Admin\AdminPagesController;
use App\Http\Controllers\Frontend\Admin\NavigationController;
use App\Http\Controllers\Frontend\Admin\AnnouncementController;

use App\Http\Controllers\MIS\ImportController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Mis Login URL
Route::get('mis-login', function() {
    return view('public.page.mislogin');
})->name('mis.login');

// Website Managment Panel Routes
Route::middleware('admin')->prefix('web-admin')->group(function() {
    Route::resource('/', 'Admin\AdminController');
    Route::resource('page', 'Admin\AdminPagesController');
    Route::resource('navigation', 'Admin\NavigationController');
    Route::resource('announcement', 'Admin\AnnouncementController');

    Route::get('page-data', [AdminPagesController::class, 'pageData'])->name('page.data');
    Route::get('navigation', [NavigationController::class, 'index'])->name('navigation.index');
    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('forgot-password', [AdminController::class, 'forgotPassword'])->name('admin.forgot.password');
    Route::get('navigation-data', [NavigationController::class, 'navigationData'])->name('navigation.data');
    Route::get('announcement-data', [AnnouncementController::class, 'announcementData'])->name('announcement.data');

    Route::get('feedback', [HomeController::class, 'feedbackGet'])->name('feedback.get');
    Route::get('feedback-data', [HomeController::class, 'feedbackDate'])->name('feedback.data');
    Route::post('/upload/file', [AdminPagesController::class, 'upload'])->name('upload.file');
    Route::post('/file/delete', [AdminPagesController::class, 'delete'])->name('file.delete');
    Route::post('administration/upload/file', [HomeController::class, 'upload'])->name('administration.upload.file');
    Route::post('administration/file/delete', [HomeController::class, 'delete'])->name('administration.file.delete');
    Route::match(['get','post'],'administration', [HomeController::class, 'administration'])->name('administration');
});

//Commmon Website Route
Route::get('{slug}','PageController@page');
Route::get('announcement/{slug}','PageController@announcement')->name('announcement');

Route::post('feedback', [HomeController::class, 'feedback'])->name('feedback');

Route::get('/', 'PageController@index')->name('public.page.home');
Route::get('our-team', 'PageController@team')->name('public.page.team');
Route::get('history', 'PageController@history')->name('public.page.history');
Route::get('about-us', 'PageController@about')->name('public.page.about');
Route::get('contact-us', 'PageController@contact')->name('public.page.contact');
Route::get('objectives', 'PageController@objective')->name('public.page.objective');
Route::get('mission-and-vision', 'PageController@mission')->name('public.page.mission');
Route::get('tenders-and-notices', 'PageController@tenders')->name('public.page.tenders');
Route::get('project-structure', 'PageController@structure')->name('public.page.structure');
Route::get('project-management', 'PageController@projectManagement')->name('public.page.projmanage');
Route::get('enhancing-infrastructure-resilience', 'PageController@enhancingInfra')->name('public.page.eninfrares');
Route::get('contingent-emergency-response-component', 'PageController@contingentEmergency')->name('public.page.conemres');
Route::get('improving-emergency-preparedness-and-response', 'PageController@improvingEmergency')->name('public.page.imempres');
Route::get('preventing-and-managing-forest-and-general-fires', 'PageController@managingForestFire')->name('public.page.forestfire');

// Language Specific Routes
Route::get('/language/{lang}', function ($lang) {
    if (!in_array($lang, ['en', 'hi'])) {
        abort(404, 'Language not supported');
    }

    Cookie::queue('lang', $lang, 60 * 24 * 7); // 7 days

    return response()->json(['message' => 'Language switched to ' . $lang, cookie('lang')]);
});


// Grievances Routes
Route::prefix('grievance')->group(function() {
    Route::get('register', 'GrievanceController@index')->name('public.grievance.register');

    Route::get('status', function() {
        return view('public.page.grievance.status');
    })->name('public.grievance.status');

    Route::post('get-details', 'GrievanceController@details')->name('public.grievance.details');

    Route::post('get-blocks', 'GrievanceController@getBlocks')->name('grievance.get.blocks');
    Route::post('get-projects', 'GrievanceController@getProjects')->name('grievance.get.projects');
    Route::post('get-districts', 'GrievanceController@getDistricts')->name('grievance.get.districts');
    Route::post('save-grievance', 'GrievanceController@save')->name('public.grievance.register.save');
    Route::post('get-subcategories', 'GrievanceController@getSubCats')->name('grievance.get.scats');
});

