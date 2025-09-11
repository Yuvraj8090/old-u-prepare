<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', 'GeneralController@index');

Route::prefix('v1')->group(function() {
    Route::get('/', 'GeneralController@versionOne');

    Route::post('login', 'AuthController@login');
    Route::post('forgot', 'AuthController@forgot');
    Route::post('reset', 'AuthController@reset');
    Route::post('resend-otp', 'AuthController@resendOTP');
    Route::post('verify-otp', 'AuthController@verifyOTP');

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('user')->group(function() {
            Route::get('/', 'AuthController@getUser');
            Route::get('dashboard', 'UserController@dashboard');

            Route::post('logout', 'AuthController@logout');

            Route::get('projects', 'ProjectController@index');
            Route::prefix('project')->group(function() {
                Route::post('images', 'ProjectController@images');
                Route::post('details', 'ProjectController@details');

                Route::middleware(['checkRole&Permission:ELEVEN, PWD-ENVIRONMENT-FOUR, PMU-ENVIRONMENT-FOUR, RWD-ENVIRONMENT-FOUR, FROEST-ENVIRONMENT-FOUR, USDMA-ENVIRONMENT-FOUR, PWD-SOCIAL-FOUR, PMU-SOCIAL-FOUR, RWD-SOCIAL-FOUR, FROEST-SOCIAL-FOUR, USDMA-SOCIAL-FOUR'])
                    ->group(function () {
                        Route::post('phases', 'PhaseController@index');
                        Route::prefix('phase')->group(function() {
                            Route::post('activities', 'PhaseController@activities');
                            Route::post('sub-activities', 'PhaseController@subActivities');

                            Route::prefix('activity')->group(function() {
                                Route::post('images', 'PhaseController@activityImages');

                                Route::prefix('image')->group(function() {
                                    Route::post('upload', 'PhaseController@uploadActivityImage');
                                });
                            });
                        });
                    });

                Route::middleware(['checkRole&Permission:SIX, PMU-LEVEL-THREE, PIU-LEVEL-THREE-PWD, PIU-LEVEL-THREE-RWD, PIU-LEVEL-THREE-FOREST, PIU-LEVEL-THREE-USDMA'])
                    ->group(function () {
                        Route::post('milestones', 'MilestoneController@index');
                        Route::prefix('milestone')->group(function() {
                            Route::post('physical-progress', 'MilestoneController@physicalProgress');
                            Route::post('physical-progress-images', 'MilestoneController@physicalProgressImages');
                            Route::post('update-physical-progress', 'MilestoneController@updatePhysicalProgress');
                            Route::post('upload-physical-progress-image', 'MilestoneController@uploadPhysicalProgressImage');
                        });
                    });
            });
        });
    });
});

