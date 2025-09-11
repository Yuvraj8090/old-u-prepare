<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Grievance routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['checkRole&Permission:grievance'])
    ->prefix('grievance-management')->group(
        function() {
            Route::get('dashboard', 'GrievanceController@index')->name('mis.grievance.dashboard');
            Route::get('record-grievance', 'GrievanceController@record')->name('mis.grievance.record');
            Route::get('manage-grievances', 'GrievanceController@manage')->name('mis.grievance.manage');
            Route::get('grievance-details/{ref_id}', 'GrievanceController@details')->name('mis.grievance.details');

            Route::post('record-grievance', 'GrievanceController@save')->name('mis.grievance.record.save');
            Route::post('assign-department', 'GrievanceController@assignDept')->name('mis.grievance.assign.department');
        }
    );
