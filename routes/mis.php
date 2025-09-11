<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminAssignProject;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\MilesStonesController;
use App\Http\Controllers\ManageLoginController;
use App\Http\Controllers\ProcurementLevelThree;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\QualityTestController;
use App\Http\Controllers\ContractAmendController;
use App\Http\Controllers\MileStoneSiteController;
use App\Http\Controllers\EnvironmentAndSocialPhotos;
use App\Http\Controllers\ProcurementParamController;
use App\Http\Controllers\ContractSecurityController;
use App\Http\Controllers\ProjectLevelthreeController;
use App\Http\Controllers\EnvironmentAndSocialDocuments;
use App\Http\Controllers\EnvironmentAndSocialController;
use App\Http\Controllers\EnvironmentAndSocialQualityTest;
use App\Http\Controllers\EnvironmentSocialTemplateController;
use App\Http\Controllers\EnvironmentSocialMilestonesController;


use App\Http\Controllers\MIS\ATRController;
use App\Http\Controllers\MIS\UserController;
use App\Http\Controllers\MIS\ReportController;
use App\Http\Controllers\MIS\ProjectController as MISProjectController;
use App\Http\Controllers\MIS\UserRoleController;
use App\Http\Controllers\MIS\DashboardController;
use App\Http\Controllers\MIS\GrievanceController;
use App\Http\Controllers\MIS\DepartmentController;
use App\Http\Controllers\MIS\RolePermissionController;
use App\Http\Controllers\MIS\UserPermissionController;
use App\Http\Controllers\MIS\ProjectTrackingController;


Route::middleware('auth')->group(function () {
    Route::post('contract/milestone/epc-boq-entry', [MilesStonesController::class, 'updateBoqEntry'])->name('milestone.boq.entry.save');
    Route::post('contract/milestone/boq-sheet-edit', [ContractController::class, 'updateBOQSheetData'])->name('contract.boqsheet.update');

    Route::prefix('roles-permission')->group(function() {
        Route::post('update', [RolePermissionController::class, 'update'])->name('permission.update');
        Route::post('delete', [RolePermissionController::class, 'delete'])->name('permission.delete');
    });

    //Department Route
    Route::prefix('department')->group(function() {
        Route::post('update', [DepartmentController::class, 'update'])->name('departments.update');
        Route::post('delete', [DepartmentController::class, 'delete'])->name('department.delete');
    });

    Route::resource('department', DepartmentController::class);

    // Admin Routes
    Route::middleware('admin')->prefix('mis-admin')->group(function() {
       

        Route::prefix('project')->group(function() {
            Route::post('delete', [ProjectController::class, 'delete'])->name('admin.project.delete');
            Route::post('cancel', [ProjectController::class, 'cancel'])->name('admin.project.cancel');
        });
    });

    Route::post('get-contract-boq-data', [ProjectController::class, 'projectBOQ'])->name('project.boq.data');

    // Routes Defined
    Route::post('get-tehsil-and-blocks', [ProjectController::class, 'getTehsilBlocks']);
    Route::post('get-project-subcategories', [ProjectController::class, 'getSubCategory']);


    // START -----------Routes Allowed For everyone-----------------------
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('home.index');
    Route::get('/update/physical/view/{id}',[ProjectLevelthreeController::class,'physicalProgressViewForm'])->name('projectLevel.physical.view.name');
    Route::get('/project/milestones/edit/{id}', [MilesStonesController::class,'edit'])->name('mile.stone.edit');
    Route::get('home/report/{id}', [HomeController::class, 'report'])->name('home.report');
    Route::get('getSubCategory/{id}', [ProjectController::class, 'getSubCategory']);
    Route::get('report/filters/{id}', [HomeController::class,'reportFilters'])->name('home.reportFilters');

    Route::get('profile', [ManageLoginController::class, 'viewProfile'])->name('my.profile');
    Route::post('profile', [ManageLoginController::class, 'saveProfile'])->name('my.profile.save');

    Route::get('change-password', [ManageLoginController::class, 'changePasswordView'])->name('login.change-password');
    Route::post('post/change-password', [ManageLoginController::class, 'changePassword'])->name('login.post.change-password');

    Route::get('milestone/physical/values/{id}',[ProjectLevelthreeController::class,'physicalProgressUpdateForm']);
     
    Route::get('milestone/financial/values/{id}',[ProjectLevelthreeController::class,'financialProgressUpdateForm']);
    Route::get('/admin/milestone/site/images/{id}',[MileStoneSiteController::class,'index']);
    Route::get('data/{id}',[ProjectController::class, 'getProjectMilestoneData']);

    Route::get('project/test/report/{id}',[QualityTestController::class, 'adminTestReport'])->name('project.test.report');
    Route::get('project/tests/{type}/{id}',[QualityTestController::class,'index'])->name('project.material.tests');
    Route::get('/project/reports/index/{type}/{id}/{projectId}',[QualityTestController::class,'indexReport'])->name('project.tests.report');
    Route::get('/project/environement/index/{id}',[QualityTestController::class,'environmentIndex'])->name('project.environmentIndex');
    Route::get('/project/environement/tests/{type}/{id}',[QualityTestController::class,'environmentTests'])->name('project.quality.environmentTests');

    // Route::get('project/details/{id}', [ProjectController::class, 'show'])->name('project.details');
    // Route::get('project/details/{id}', [ProjectController::class, 'show'])->name('project.details');
    Route::get('project/details/three/{id}', [ProjectController::class, 'show'])->name('project.details.three');
    Route::get('project/details/{id}', [ProjectController::class, 'show'])->name('project.details');

    Route::get('all/tests/{moduleType}/{type}/{id}',[EnvironmentAndSocialQualityTest::class,'EnvironmentSocialAdminIndex']);
    Route::get('all/sub/tests/{moduleType}/{projectId}/{type}/{id}',[EnvironmentAndSocialQualityTest::class,'EnvironmentSocialAdminSubTests']);

    Route::get('report/images/{projectId}/{id}',[EnvironmentAndSocialQualityTest::class,'indexPhotos']);
    Route::get('admin/report/images/{moduletype}/{projectId}/{id}',[EnvironmentAndSocialQualityTest::class,'indexPhotosAdmin']);
    // END -----------Routes Allowed For everyone-----------------------


    // START -----------Routes Allowed For PMU LVL One only-----------------------
    Route::middleware([
        'checkRole&Permission:ONE,PMU-LEVEL-ONE,project_creator'
    ])->group(function () {
        Route::get('project/milestones', [MilesStonesController::class, 'index'])->name('project.milestones');
        Route::resource('project', ProjectController::class);

        Route::get('environment/social/documents/{id}',[EnvironmentAndSocialDocuments::class,'viewDocuments']);
        Route::get('environment/social/photos/{id}',[EnvironmentAndSocialPhotos::class,'viewPhotos']);

        Route::get('project/indexAjax', [ProjectController::class, 'indexAjax']);
        Route::get('teshsilsAndBlocks/{id}', [ProjectController::class, 'getTehsilBlocks']);
        Route::get('project/view/{id}', [ProjectController::class, 'view'])->name('project.view');
        Route::get('manage/project/edit', [ProjectController::class, 'index'])->name('p.edit');
        Route::post('project/update/{id}', [ProjectController::class, 'update'])->name('project.update.new');

        Route::get('assign-project/index/{id}', [AdminAssignProject::class, 'index'])->name('assign.project.index');
        Route::get('assign-project/user/{projectId}/{AssignUserId}', [AdminAssignProject::class, 'updateProjectUser'])->name('assign.project.user');
        // Route::get('assign-project/user/{projectId}/{AssignUserId}', [AdminAssignProject::class, 'updateProjectUser'])->name('assign.project.user');
        Route::get('assign-project/view/{projectId}', [AdminAssignProject::class, 'viewAssignedProjects'])->name('assign.project.view');
        Route::get('department/{id}', [HomeController::class, 'dashboardDepartment'])->name('dashboard.department');

        // Route::resource('manage-logins', ManageLoginController::class);
        // Route::post('manage/login/update/{id}', [ManageLoginController::class, 'update'])->name('pmu.login.update');
        // Route::get('login/status/{id}/{status}', [ManageLoginController::class, 'changeStatus'])->name('login.changeStatus');
    });
    // END -----------Routes Allowed For PMU LVL One only-----------------------

    // START -----------Routes Allowed For PROCUREMENT LVL 2 Only-----------------------
    Route::middleware([
        'checkRole&Permission:THREE,PROCUREMENT-LEVEL-TWO'
    ])->group(function () {
        // Route::get('/procurement/dashboard',[HomeController::class,'procurementDashboard'])->name('procurement.dashboard');
        // Route::resource('procurement',ProcurementController::class);
        // Route::resource('procurement-params',ProcurementParamController::class);
        // Route::post('procurement/update/{id}',[ProcurementController::class,'update'])->name('procurement.update');

        Route::get('procurement/index',[ProcurementController::class,'index'])->name('procurement.index.one');
        Route::get('procurement/program/{id}', [ProcurementController::class, 'WorkingProgramForm'])->name('procurement.program');
    });
    // END -----------Routes Allowed For PROCUREMENT LVL 2 Only-----------------------

    // START -----------Routes Allowed For PMU LVL-1 & PROCUREMENT LVL 1-----------------------
    Route::middleware([
        'checkRole&Permission:TWO,PMU-LEVEL-ONE,PMU-LEVEL-TWO,PIU-LEVEL-TWO-PWDPIU-LEVEL-TWO-RWD,PIU-LEVEL-TWO-FOREST,PIU-LEVEL-TWO-USDMA,ENVIRONMENT-LEVEL-TWO,SOCIAL-LEVEL-TWO'
    ])->group(function () {
        // Route::resource('manage-logins', ManageLoginController::class);
        // Route::post('manage/login/update/{id}', [ManageLoginController::class, 'update'])->name('pmu.login.update');
        // Route::get('login/status/{id}/{status}', [ManageLoginController::class, 'changeStatus'])->name('login.changeStatus');
        // Route::get('project/details/{id}', [ProjectController::class, 'show'])->name('project.details');
        // Route::get('/milestone/physical/values/{id}',[ProjectLevelthreeController::class,'physicalProgressUpdateForm']);
        // Route::get('/milestone/financial/values/{id}',[ProjectLevelthreeController::class,'financialProgressUpdateForm']);
        // Route::get('/milestone/site/images/{id}',[MileStoneSiteController::class,'index']);

        // Route::get('/project/milestones/edit/{id}',[MilesStonesController::class,'edit'])->name('mile.stone.edit');
    });
    // END -----------Routes Allowed For PMU LVL-1 & PROCUREMENT LVL 1-----------------------


    // START -----------Routes Allowed For PROCUREMENT LVL 2 & 3 Only -----------------------
    // Route::middleware(['checkRole&Permission:FOUR,PROCUREMENT-LEVEL-TWO,PROCUREMENT-LEVEL-THREE'])->group(function () {


    // });
    // END -----------Routes Allowed For PROCUREMENT LVL 2 & 3 Only -----------------------

    // Route::middleware(['checkRole&Permission:FIVE,PROCUREMENT-LEVEL-THREE'])->group(function () {
    //     Route::resource('procurement-params', ProcurementParamController::class);
    //     Route::post('procurement/update/{id}', [ProcurementController::class, 'update'])->name('procurement.update');
    //     Route::resource('procurement', ProcurementController::class);
    //     Route::get('contract/edit/{id}', [ContractController::class, 'edit'])->name('contract.edit');
    //     Route::post('contract/update/{id}', [ContractController::class, 'update'])->name('contract.update');
    //     Route::get('procurement/program/{id}', [ProcurementController::class, 'WorkingProgramForm'])->name('procurement.program');
    //     Route::resource('contract', ContractController::class);
    // });


    // START -----------Routes Allowed For All PMU & PIU LVL 2 Only -----------------------
    Route::middleware([
        'checkRole&Permission:FIVE,PIU-LEVEL-TWO-PWD,PIU-LEVEL-TWO-RWD,PMU-LEVEL-TWO,PIU-LEVEL-TWO-FOREST,PIU-LEVEL-TWO-USDMA'
    ])->group(function () {
        Route::get('show/department/details/{id}', [ProjectController::class, 'showDepartmentDetails'])->name('project.department.details');
        Route::get('define/project', [ProjectController::class, 'index'])->name('project.index.two');
        Route::get('department/assign/project', [ProjectController::class, 'level2Index'])->name('project.level2Index');

        Route::get('project-{project_id}/contract/update-boq-sheet/', [ContractController::class, 'updateBOQSheet'])->name('project.contract.boqsheet.update');
        Route::get('define/project/view/{id}', [ProjectController::class, 'defineProjectView'])->name('project.defineProjectView');
        Route::post('/define/project/create/{id}', [ProjectController::class, 'defineProjectByLvlTwo'])->name('project.defineProjectByLvlTwo');

        Route::get('/project/milestones/create/{id}',[MilesStonesController::class,'create'])->name('mile.stone.create');
        Route::post('/project/milestones/store/{id}',[MilesStonesController::class,'store'])->name('mile.stone.store');

        Route::post('/project/milestones/documents/{id}',[MilesStonesController::class,'documentAdd'])->name('mile.stone.documents');

        Route::post('/milestones/documents/updated',[MilesStonesController::class,'UpdateDocument'])->name('mile.stone.updated');

        // Route::get('/project/milestones/edit/{id}',[MilesStonesController::class,'edit'])->name('mile.stone.edit');
        Route::post('/project/milestones/update',[MilesStonesController::class,'update'])->name('mile.stone.update');
        Route::post('/project/milestones/update/{id}',[MilesStonesController::class,'update'])->name('mile.stone.update');
        Route::get('/project/milestones/delete/{id}',[MilesStonesController::class,'destroy'])->name('mile.stone.destroy');
        Route::get('/project/work/progress',[WorkController::class,'index'])->name('work.progress');

        Route::get('/finance/index',[FinanceController::class,'index'])->name('finance.index');
        Route::post('/finance/store',[FinanceController::class,'store'])->name('finance.store');
        Route::get('/finance/edit/{id}',[FinanceController::class,'edit'])->name('finance.edit');
        Route::post('/finance/update/{id}',[FinanceController::class,'update'])->name('finance.update.new');
        Route::post('/AssignProjectlevelThree',[ProjectController::class,'AssignProjectlevelThree'])->name('AssignProjectlevelThree.update');

        Route::get('define/project/milestone-{id}/site-images', [MilestoneSiteController::class, 'milestoneImages'])->name('project.define.milestone.site.images');
        Route::post('define/project/milestone/site-image/delete', [MileStoneSiteController::class, 'deleteMilestoneImage'])->name('project.define.milestone.site.image.delete');
    });
    // END -----------Routes Allowed For All PMU & PIU LVL 2 Only -----------------------



    // START -----------Routes Allowed For All PMU & PIU LVL 3 Only -----------------------
    Route::middleware([
        'checkRole&Permission:SIX,PMU-LEVEL-THREE,PIU-LEVEL-THREE-PWD,PIU-LEVEL-THREE-RWD,PIU-LEVEL-THREE-FOREST,PIU-LEVEL-THREE-USDMA'
    ])->group(function () {
        // Route::get('/milestone/physical/values/{id}',[ProjectLevelthreeController::class,'physicalProgressUpdateForm']);
        // Route::get('/milestone/financial/values/{id}',[ProjectLevelthreeController::class,'financialProgressUpdateForm']);
        // Route::get('/milestone/site/images/{id}',[MileStoneSiteController::class,'index']);

        Route::get('/update/physical/progress/{id}',[ProjectLevelthreeController::class,'physicalProgressUpdateForm'])->name('projectLevel.physical.view');
        
        Route::get('/update/financial/progress/{id}',[ProjectLevelthreeController::class,'financialProgressUpdateForm'])->name('projectLevel.financial.view');

        Route::post('/update/physical/{id}',[ProjectLevelthreeController::class,'PhysicalProressUpdate'])->name('projectLevel.physical');
        Route::post('/update/financial/{id}',[ProjectLevelthreeController::class,'FinancialProressUpdate'])->name('projectLevel.financial');

        Route::get('/update/project/create/{id}',[ProjectLevelthreeController::class,'create'])->name('projectLevel.create');
        Route::get('update/project/boq-sheet/{id}', [ProjectLevelthreeController::class, 'updateBOQSheet'])->name('projectLevel.update.boqsheet');
	    Route::get('update/project/boq-sheet/financial/{id}', [ProjectLevelthreeController::class, 'boqFinancialUpdate'])->name('projectLevel.financial.boqsheet');
        Route::get('/update/project/edit/{id}',[ProjectLevelthreeController::class,'edit'])->name('projectLevel.edit');
        Route::post('/update/milestones/{id}',[ProjectLevelthreeController::class,'store'])->name('projectLevel.store');
        // Route::get('/project/milestones/edit/{id}',[MilesStonesController::class,'edit'])->name('mile.stone.edit');

        Route::get('/update/project/milestones',[ProjectLevelthreeController::class,'index'])->name('projectLevel.index');
        Route::get('work/progress',[ProjectLevelthreeController::class,'WorkProgress'])->name('work.progress');

        Route::get('quality/update/{type}/{id}',[QualityTestController::class,'index'])->name('quality.test');
        Route::post('/quality/add/',[QualityTestController::class,'store'])->name('quality.add');
        Route::get('/quality/delete/{id}',[QualityTestController::class,'delete'])->name('quality.delete');

        Route::get('/quality/report/index/{type}/{id}/{projectId}',[QualityTestController::class,'indexReport'])->name('report.indexReport');
        Route::post('/quality/report/add',[QualityTestController::class,'storeReport'])->name('quality.storeReport');
        Route::post('/quality/report/update',[QualityTestController::class,'updateReport'])->name('quality.updateReport');

        Route::get('/quality/report/delete/{id}',[QualityTestController::class,'ReportDelete'])->name('quality.ReportDelete');
        Route::get('/quality/environement/index/{id}',[QualityTestController::class,'environmentIndex'])->name('report.environmentIndex');
        Route::post('/quality/environement/add/',[QualityTestController::class,'environmentStore'])->name('quality.environmentStore');
        Route::get('/quality/environement/tests/{type}/{id}',[QualityTestController::class,'environmentTests'])->name('quality.environmentTests');
        Route::post('/quality/environment/report/add',[QualityTestController::class,'storeEnvironmentReport'])->name('quality.storeEnvironmentReport');
        Route::get('/quality/environement/delete/{id}',[QualityTestController::class,'environmentDelete'])->name('quality.environmentDelete');

        Route::post('/site/images/add/',[MileStoneSiteController::class,'store'])->name('site.add');
        Route::get('/site/index/{id}',[MileStoneSiteController::class,'index'])->name('site.index');
        Route::get('/site/milestone-{id}/images', [MilestoneSiteController::class, 'milestoneImages'])->name('site.milestone.images');

        Route::post('site/image/update',[MileStoneSiteController::class,'update'])->name('site.update');
    });
    // END -----------Routes Allowed For All PMU & PIU LVL 3 Only -----------------------


    // START ----------Routes Allowed for ALL Procuremnt LVL 3 Only -----------------------
    Route::middleware([
        'checkRole&Permission:SEVEN,PMU-PROCUREMENT-LEVEL-THREE,PWD-PROCUREMENT-LEVEL-THREE,RWD-PROCUREMENT-LEVEL-THREE,FOREST-PROCUREMENT-LEVEL-THREE,USDMA-PROCUREMENT-LEVEL-THREE'
    ])->group(function () {
        Route::get('procurement/program/{id}', [ProcurementController::class, 'WorkingProgramForm'])->name('procurement.program');
        Route::resource('procurement', ProcurementController::class);
        Route::get('procurement/program/update/{id}', [ProcurementController::class, 'WorkingProgramEditView'])->name('procurement.work-program.update');

        Route::get('procurement/level/three/projects',[ProcurementLevelThree::class,'index'])->name('procure.three.index');

        Route::get('procurement/three/work-program/edit/{id}',[ProcurementLevelThree::class,'edit'])->name('procure.level.three.edit');
        Route::post('procurement/update/single/three', [ProcurementLevelThree::class, 'updateSingleThree'])->name('procurement.update.single.update');
        Route::post('procurement/upload/docuement', [ProcurementLevelThree::class, 'UploadBidDocument'])->name('procurement.update.bid.document');
        Route::get('procurement/create/{id}', [ProcurementController::class, 'create'])->name('procurement.create');
        Route::post('procurement/store/{id}', [ProcurementController::class, 'store'])->name('procurement.store');

        Route::get('procurement-project/index',[ ProcurementParamController::class, 'index'])->name('project.procuremnt.level.three');
        Route::get('/prourement/project/work/progress',[WorkController::class,'index'])->name('prourement.work.progress');
        Route::get('procurement-project/edit/{id}',[ ProcurementParamController::class, 'edit'])->name('project.procuremnt.level.three.edit');
        Route::post('procurement-project/update/{id}',[ ProcurementParamController::class, 'update'])->name('project.procuremnt.level.three.update');
        Route::get('contract/create/{id}', [ContractController::class, 'create'])->name('contract.create');
        Route::get('contract/view/{id}', [ContractController::class, 'view'])->name('procurement.view');
        Route::post('contract/store/{id}', [ContractController::class, 'store'])->name('contract.store');
        Route::get('contract/close/{id}', [ContractController::class, 'cancelContactView'])->name('contract.cancelContactView');
        Route::post('contract/close/post/{id}', [ContractController::class, 'CanacelContract'])->name('contract.CanacelContract');
        Route::post('contract-security/store/{id}', [ContractSecurityController::class, 'store'])->name('contract.security.store');
        Route::get('contract-security/index/{id}', [ContractSecurityController::class, 'index'])->name('contract.security.create');
        Route::get('contract-security/edit/{id}', [ContractSecurityController::class, 'edit'])->name('contract.security.edit');
        Route::post('contract-security/update/{id}', [ContractSecurityController::class, 'update'])->name('contract.security.update');
        Route::get('contract-amend/index/{id}', [ContractAmendController::class, 'index'])->name('contract-amend.index');
        Route::post('contract-amend/store', [ContractAmendController::class, 'store'])->name('contract-amend.store');
        Route::resource('contract-security', ContractSecurityController::class);
        Route::get('contract-security/delete/{id}', [ContractSecurityController::class, 'mediaDelete'])->name('contract.security.delete');

        Route::post('procurement/update/single', [ProcurementParamController::class, 'updateSingle'])->name('procurement.update.single');
        Route::get('procurement/param/delete/{id}', [ProcurementParamController::class, 'delete'])->name('procurement.param.delete');
        Route::resource('procurement-params', ProcurementParamController::class);
        Route::post('procurement/update/{id}', [ProcurementController::class, 'update'])->name('procurement.update');
        Route::resource('procurement', ProcurementController::class);
        Route::get('contract/edit/{id}', [ContractController::class, 'edit'])->name('contract.edit');
        Route::post('contract/update/{id}', [ContractController::class, 'update'])->name('contract.update');

        Route::resource('contract', ContractController::class);
    });
    // END ----------Routes Allowed for ALL Procuremnt LVL 3 Only -----------------------


    // NEW CODE 14/FEB/2024
    // START ----------Routes Allowed for ENVIRONMENT & SOCIAL LVL 2 Only -----------------------
    Route::middleware([
        'checkRole&Permission:EIGHT,ENVIRONMENT-LEVEL-TWO,SOCIAL-LEVEL-TWO'
    ])->group(function () {
        Route::get('/projects/social/environment',[EnvironmentAndSocialController::class,'index'])->name('mile.social.environment');
        Route::get('/projects/social/environment/details/{id}',[EnvironmentAndSocialController::class,'ViewPage'])->name('es.ViewPage');
        Route::get('milestone/view/documents/{id}',[EnvironmentSocialMilestonesController::class,'viewDocuments']);
        Route::get('milestone/view/photos/{id}',[EnvironmentSocialMilestonesController::class,'viewPhotos']);
        Route::get('milestone/delete/photo/{id}',[EnvironmentSocialMilestonesController::class,'deleteMilestonePhoto']);

        Route::get('template/index',[EnvironmentSocialTemplateController::class,'index']);
        Route::post('template/store',[EnvironmentSocialTemplateController::class,'store']);
        Route::post('template/update',[EnvironmentSocialTemplateController::class,'update']);
        Route::get('template/delete/{id}',[EnvironmentSocialTemplateController::class,'delete']);

        // Route::get('environment-social/department/{id}', [EnvironmentAndSocialController::class, 'showDepartmentDetails'])->name('project.environment-social');
        // Route::get('environment-social/assign/project/{id}', [EnvironmentAndSocialController::class, 'AssignProjectlevelThree'])->name('project.environment.AssignProjectlevelThree');
    });
    // END ----------Routes Allowed for ENVIRONMENT & SOCIAL LVL 2 Only -----------------------


    // START ----------Routes Allowed for ENVIRONMENT & SOCIAL LVL 3 Only -----------------------
    Route::middleware([
        'checkRole&Permission:NINE,PWD-ENVIRONMENT-LEVEL-THREE,PMU-ENVIRONMENT-LEVEL-THREE,RWD-ENVIRONMENT-LEVEL-THREE,FROEST-ENVIRONMENT-LEVEL-THREE,USDMA-ENVIRONMENT-LEVEL-THREE,PWD-SOCIAL-LEVEL-THREE,PMU-SOCIAL-LEVEL-THREE,RWD-SOCIAL-LEVEL-THREE,FROEST-SOCIAL-LEVEL-THREE,USDMA-SOCIAL-LEVEL-THREE'
    ])->group(function () {
        Route::get('{type}/projects/update',[EnvironmentAndSocialController::class,'index'])->name('environment-social.three.index');
        Route::get('update/milestones/progress/{id}',[EnvironmentSocialMilestonesController::class,'editLevelThree']);
        Route::post('progress/update/milestones',[EnvironmentSocialMilestonesController::class,'updateSingleThree']);

        Route::get('es/milestone/images/{id}',[EnvironmentAndSocialPhotos::class,'index']);
        Route::post('es/milestone/images/store',[EnvironmentAndSocialPhotos::class,'store']);
        Route::post('es/milestone/images/update',[EnvironmentAndSocialPhotos::class,'update']);
        Route::get('es/milestone/images/destroy/{id}',[EnvironmentAndSocialPhotos::class,'destroy']);

        Route::get('es/milestone/documents/{id}',[EnvironmentAndSocialDocuments::class,'index']);
        Route::post('es/milestone/documents/store',[EnvironmentAndSocialDocuments::class,'store']);
        Route::post('es/milestone/documents/update',[EnvironmentAndSocialDocuments::class,'update']);
        Route::get('es/milestone/documents/destroy/{id}',[EnvironmentAndSocialDocuments::class,'destroy']);

        Route::get('/project/milestones/{id}',[EnvironmentAndSocialController::class,'createMileStones'])->name('mile.stone.index');
        Route::post('/project/milestones/social/environment/store/{id}',[EnvironmentAndSocialController::class,'milestoneStore'])->name('mile.milestoneStore');

        Route::get('social/environment/define/{id}',[EnvironmentAndSocialController::class,'defineProjectStoreView']);
        Route::post('/social/environment/define/store',[EnvironmentAndSocialController::class,'storeDefineProject']);

        Route::get('social/environment/define/edit/{id}',[EnvironmentAndSocialController::class,'defineProjectEditView']);
        Route::post('/social/environment/define/update/{id}',[EnvironmentAndSocialController::class,'updateDefineProject']);

        Route::get('es/{type}/{id}',[EnvironmentSocialMilestonesController::class,'index'])->name('es.index');
        Route::post('/es/store/',[EnvironmentSocialMilestonesController::class,'store'])->name('es.store');
        Route::get('edit/es/{id}',[EnvironmentSocialMilestonesController::class,'edit'])->name('es.edit');

        Route::get('/environment-social/milestones/edit/{id}',[EnvironmentAndSocialController::class,'edit'])->name('mile.environment.edit');
        Route::post('/environment-social/milestones/update/{id}',[EnvironmentAndSocialController::class,'update'])->name('mile.environment.update');

        Route::get('quality/tests/{id}',[EnvironmentAndSocialQualityTest::class,'environmentIndex']);
        Route::get('tests/{type}/{id}',[EnvironmentAndSocialQualityTest::class,'EnvironmentSocialTests']);
        Route::get('tests/others/{type}/{id}/{testId}',[EnvironmentAndSocialQualityTest::class,'EnvironmentSocialChildTests']);

        Route::post('es/tests/create',[EnvironmentAndSocialQualityTest::class,'store']);
        Route::get('es/tests/delete/{id}',[EnvironmentAndSocialQualityTest::class,'environmentDelete']);
        Route::post('es/tests/update',[EnvironmentAndSocialQualityTest::class,'update']);

        Route::get('all/photos/{projectId}',[EnvironmentAndSocialQualityTest::class,'allPhotos']);

        // Route::get('procurement-project/index',[ ProcurementParamController::class, 'index'])->name('project.procuremnt.level.three');
    });
    // END ----------Routes Allowed for ENVIRONMENT & SOCIAL LVL 2 Only -----------------------


    Route::middleware([
        'checkRole&Permission:TEN,PMU-LEVEL-ONE,PWD-ENVIRONMENT-LEVEL-THREE,PMU-ENVIRONMENT-LEVEL-THREE,RWD-ENVIRONMENT-LEVEL-THREE,FROEST-ENVIRONMENT-LEVEL-THREE,USDMA-ENVIRONMENT-LEVEL-THREE,PWD-SOCIAL-LEVEL-THREE,PMU-SOCIAL-LEVEL-THREE,RWD-SOCIAL-LEVEL-THREE,FROEST-SOCIAL-LEVEL-THREE,USDMA-SOCIAL-LEVEL-THREE,PMU-LEVEL-TWO,PIU-LEVEL-TWO-PWD,PIU-LEVEL-TWO-RWD,PIU-LEVEL-TWO-FOREST,PIU-LEVEL-TWO-USDMA,ENVIRONMENT-LEVEL-TWO,SOCIAL-LEVEL-TWO'
    ])->group(function () {
        Route::resource('manage-logins', ManageLoginController::class);
        Route::post('manage/login/update/{id}', [ManageLoginController::class, 'update'])->name('pmu.login.update');
        Route::get('login/status/{id}/{status}', [ManageLoginController::class, 'changeStatus'])->name('login.changeStatus');

    });

    Route::middleware([
        'checkRole&Permission:ELEVEN,PWD-ENVIRONMENT-FOUR,PMU-ENVIRONMENT-FOUR,RWD-ENVIRONMENT-FOUR,FROEST-ENVIRONMENT-FOUR,USDMA-ENVIRONMENT-FOUR,PWD-SOCIAL-FOUR,PMU-SOCIAL-FOUR,RWD-SOCIAL-FOUR,FROEST-SOCIAL-FOUR,USDMA-SOCIAL-FOUR'
    ])->group(function () {
            Route::get('four/projects',[EnvironmentAndSocialController::class,'fourIndex']);
            Route::get('quality/four/tests/{id}',[EnvironmentAndSocialQualityTest::class,'environmentFourIndex']);
            Route::get('tests/four/{type}/{id}',[EnvironmentAndSocialQualityTest::class,'EnvironmentSocialFourTests']);
            Route::get('tests/four/others/{type}/{id}/{testId}',[EnvironmentAndSocialQualityTest::class,'EnvironmentSocialFourChildTests']);
            Route::post('tests/storeRReport',[EnvironmentAndSocialQualityTest::class,'storeRReport']);

            Route::get('test/images/{projectId}/{id}',[EnvironmentAndSocialQualityTest::class,'indexPhotos']);
            Route::get('test/image/delete/{id}',[EnvironmentAndSocialQualityTest::class,'deletePhotos']);
            Route::post('test/images/upload',[EnvironmentAndSocialQualityTest::class,'storePhotos']);
            Route::post('test/images/update',[EnvironmentAndSocialQualityTest::class,'updatePhotos']);
            Route::get('template/four/index',[EnvironmentSocialTemplateController::class,'fourIndex']);
    });


    // Routes for Grievance Management in MIS
    Route::prefix('mis')->group(function() {
        Route::resource('users-roles', UserRoleController::class);
        Route::resource('roles-permissions', RolePermissionController::class);
        Route::resource('users-permissions', UserPermissionController::class);

        Route::prefix('dashboard')->group(function() {
            Route::get('/', [HomeController::class, 'dashboard'])->name('mis.index');

            Route::prefix('pd')->group(function() {
                Route::get('components', [DashboardController::class, 'components'])->name('mis.dashboard.pd.components');
                Route::prefix('component')->group(function() {
                    Route::post('save', [DashboardController::class, 'saveComponent'])->name('mis.dashboard.pd.component.save');
                    Route::get('{comp_id}/edit', [DashboardController::class, 'editComponent'])->name('mis.dashboard.pd.component.edit');
                    
                    Route::prefix('piu')->group(function() {
                        Route::post('save', [DashboardController::class, 'savePIU'])->name('mis.dashboard.pd.component.piu.save');
                        Route::get('{piu_id}/edit', [DashboardController::class, 'editpiu'])->name('mis.dashboard.pd.component.piu.edit');
                    });
                });
            });
        });

        Route::prefix('role')->group(function() {
            Route::post('update', [RolePermissionController::class, 'update'])->name('mis.role.update');
            Route::post('delete', [RolePermissionController::class, 'delete'])->name('mis.role.delete');
        });

        Route::get('users', [UserController::class, 'index'])->name('mis.users');

        Route::prefix('user-{username}')->middleware('checkpoint:users_permissions')->group(function() {
            Route::prefix('permissions')->group(function() {
                Route::get('edit', [UserPermissionController::class, 'edit'])->name('mis.user.permissions.edit');

                Route::post('update', [UserPermissionController::class, 'update'])->name('mis.user.permissions.update');
            });
        });

        Route::get('grievances', [GrievanceController::class, 'index'])->name('mis.grievances');
        Route::post('/grievances/{id}/update-status', [GrievanceController::class, 'updateStatus']);
        Route::post('/grievances/{id}/remark', [GrievanceController::class, 'addRemarkLog']);


        Route::prefix('grievance')->group(function() {
            Route::get('details/{ref_id}', [GrievanceController::class, 'details'])->name('mis.grievance.detail');

            Route::post('forward', [GrievanceController::class, 'forward'])->name('mis.grievance.forward');
            Route::post('action-report', [GrievanceController::class, 'actionReport'])->name('mis.grievance.action');
        });


        Route::get('projects', [MISProjectController::class, 'index'])->name('mis.projects');

        Route::prefix('project')->group(function() {
            Route::prefix('details')->group(function() {
                Route::get('{project_id}', [MISProjectController::class, 'show'])->name('mis.project.detail');

                Route::get('{project_id}/milestones', [MISProjectController::class, 'viewMilestones'])->name('mis.project.detail.milestones');
                Route::get('{project_id}/milestones/boq-sheet', [MISProjectController::class, 'viewMilestonesBOQ'])->name('mis.project.detail.milestones.boq');
                Route::get('{project_id}/{type}-quality-reports', [MISProjectController::class, 'viewQualityReports'])->name('mis.project.detail.quality');
                Route::get('{project_id}/{type}-safeguard-compliances', [MISProjectController::class, 'viewCompliances'])->name('mis.project.detail.compliances');
                Route::get('{project_id}/{type}-safeguard-compliances-sheet', [MISProjectController::class, 'viewCompliancesSheet'])->name('mis.project.detail.compliances.sheet');
                Route::get('{project_id}/milestone-{milestone_id}/financial', [MISProjectController::class, 'viewMilestoneFinancial'])->name('mis.project.detail.milestone.financial');
            });

            Route::prefix('contract')->group(function() {
                // Routes for Adding Securities & Form of Securities
                Route::get('security-types', [ContractSecurityController::class, 'securityTypes'])->name('contract.security.types');

                Route::prefix('security-types')->group(function() {
                    Route::post('save', [ContractSecurityController::class, 'saveSecurityType'])->name('contract.security.type.save');
                    Route::post('delete', [ContractSecurityController::class, 'deleteSecurityType'])->name('contract.security.type.delete');

                    Route::get('forms', [ContractSecurityController::class, 'securityTypeForms'])->name('contract.security.type.forms');
                });

                Route::prefix('milestone')->group(function() {
                    Route::post('add-boq-sheet-record', [ContractController::class, 'addBOQSheetRow'])->name('contract.boqsheet.record.add');
                });

                Route::post('update-physical-progress', [ContractController::class, 'savePhysicalProgress'])->name('contract.physical.save');
                Route::post('delete-physical-progress', [ContractController::class, 'deletePhysicalProgress'])->name('contract.physical.delete');
                Route::post('validate-physical-progress-value', [ContractController::class, 'validateProgress'])->name('contract.physical.progress.validate');
                Route::post('physical-progress-activity-stages', [ContractController::class, 'activityStages'])->name('contract.activity.stages');
	        });

            Route::prefix('tracking')->group(function() {
                Route::post('safeguard-entry-save', [ProjectTrackingController::class, 'saveSheetEntry'])->name('mis.project.tracking.safeguard.entry.save');
                Route::post('safeguard-entry-sheet', [ProjectTrackingController::class, 'getEntrySheet'])->name('mis.project.tracking.safeguard.entry.sheet');
                Route::post('safeguard-entry-images', [ProjectTrackingController::class, 'getEntryImages'])->name('mis.project.tracking.safeguard.entry.image');
                Route::post('safeguard-entry-images-save', [ProjectTrackingController::class, 'saveEntryImages'])->name('mis.project.tracking.safeguard.entry.image.save');
                Route::post('safeguard-entry-image-delete', [ProjectTrackingController::class, 'deleteEntryImages'])->name('mis.project.tracking.safeguard.entry.image.delete');

                Route::prefix('{type}-safeguard')->group(function() {
                    Route::get('entry-sheet-{project_id}', [ProjectTrackingController::class, 'entrySheet'])->name('mis.project.tracking.safeguard.entry.sheet');
                    Route::get('project-{project_id}-overview', [ProjectTrackingController::class, 'overview'])->name('mis.project.tracking.safeguard.overview');
                });

                Route::get('update-quality-tests', [ProjectTrackingController::class, 'qualityTests'])->name('mis.project.tracking.tests');
                Route::get('update-{type}-progress', [ProjectTrackingController::class, 'projectProgress'])->name('mis.project.tracking.progress.update');
            });

            Route::prefix('action-rask-report')->group(function() {
                Route::get('entry-form', [ATRController::class, 'entryForm'])->name('atr.entry.form');
                Route::get('view-report', [ATRController::class, 'viewReport'])->name('atr.report');
                Route::get('edit-report-{id}', [ATRController::class, 'editEntry'])->name('atr.entry.edit');

                Route::post('save-monthly-entry', [ATRController::class, 'saveEntry'])->name('atr.entry.save');
                Route::post('get-sub-components-for-component', [ATRController::class, 'getSubComponents'])->name('atr.entry.component.sub');
            });

            Route::prefix('report')->group(function() {
                Route::get('{type}-compliances', [ReportController::class, 'envSoc'])->name('mis.report.env-soc');
            });
        });
    });
});
