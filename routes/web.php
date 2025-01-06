<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SuperAdministrator\SuperAdministratorController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SuperAdministrator\CompanyProfileController;
use App\Http\Controllers\SuperAdministrator\EmailTemplateController;
use App\Http\Controllers\SuperAdministrator\ApplicationStatusController;
use App\Http\Controllers\Administrator\AdministratorController;
use App\Http\Controllers\Administrator\DocumentSetupController;
use App\Http\Controllers\Administrator\ComplaintTypeController;
use App\Http\Controllers\Administrator\UserCategoryController;
use App\Http\Controllers\Administrator\UserSubCategoryController;
use App\Http\Controllers\Administrator\OrganisationUnitController;
use App\Http\Controllers\Administrator\DepartmentController;
use App\Http\Controllers\Administrator\DocumentTypeController;
use App\Http\Controllers\Administrator\RestrictionController;
use App\Http\Controllers\Administrator\BranchesController;
use App\Http\Controllers\DependentDropdownController;
use App\Http\Controllers\ProductRequestsController;
use App\Http\Controllers\Products\DocumentApplicationsController;
//~documents products
use App\Http\Controllers\Products\EducatorsController;
use App\Http\Controllers\Products\DeathClaimsController;
use App\Http\Controllers\Products\ClaimRequestsController;
use App\Http\Controllers\Products\TermAssurancesController;
use App\Http\Controllers\Products\MandateRequestsController;
use App\Http\Controllers\Products\PersonalAccidentsController;
use App\Http\Controllers\Products\RefundRequestsController;
use App\Http\Controllers\Products\SipsController;
use App\Http\Controllers\Products\TppsController;
use App\Http\Controllers\Products\TransitionsController;
use App\Http\Controllers\Products\TravelInsurancesController;
use App\Http\Controllers\Products\KeymansController;
use App\Http\Controllers\Products\FidoSipsController;
use App\Http\Controllers\Products\CorporateDueDiligencesController;
use App\Http\Controllers\DocumentPreviewController;
use App\Http\Controllers\Administrator\DocumentProductsController;
use App\Http\Controllers\SLAM\PushToSlamComtroller;
use App\Http\Controllers\ActionMenusController;
use App\Http\Controllers\GeneratePDF\ProposalToPDFController;
use App\Http\Controllers\GeneratePDF\MandateToPDFController;
use App\Http\Controllers\GeneratePDF\ProposalAndMandateToPDFController;
use App\Http\Controllers\ComplaintsController;
use App\Http\Controllers\verificationController;
use App\Http\Controllers\TestS3Controller;

//~
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
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/', function () {
    return redirect('login');
    // return view('products.tpp');
    // return view('welcome');
    // return view('auth.request-login-code');
});

Route::get('/upload', function(){
    return view('test-upload');
}); 

Route::get('/pdf-test', [TestS3Controller::class, 'generatePdf'])->name('pdf-test');   
Route::post('/test/file/upload', [TestS3Controller::class, 'saveFileOnS3'])->name('dashboard'); 

Route::get('/convert', [TestS3Controller::class, 'convertAmount'])->name('convert');   
Route::get('/test/nia/api', [TestS3Controller::class, 'testNiaApi'])->name('test.nia.api');  
Route::post('/test/nia/api/post', [TestS3Controller::class, 'testNiaApiPost'])->name('test.nia.api.post');    


Route::get('/send-test-email', function () {
    $details = [
        'title' => 'STAK authentication code',
        'code' => '123456'
    ];

    \Mail::to('myschool22ml@gmail.com')->send(new \App\Mail\SendOTP($details));

    return 'Test email sent successfully!';
});


// Route::get('/educator', function () {
//     return view('products.educator');
// });


Route::get('/new-request', function () {
    return view('products.new-product');
});
Route::get('/new-request-2', function () {
    return view('products.new-product-2');
});

////
// Route::get('/new-request', [ProductRequestsController::class, 'initiateRequest'])->name('initiate-request'); 

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [IndexController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/requested-documents', [IndexController::class, 'requestedDocuments'])->name('requested-documents'); 
    Route::get('/dashboard/fa-dashboard', [IndexController::class, 'faDashboard'])->name('fa-dashboard'); 
    Route::get('/dashboard/my-pending-bin', [IndexController::class, 'pendingBin'])->name('pending-bin-dashboard'); 
    Route::post('/dashboard/get/my-pending-bin', [IndexController::class, 'ajaxPendingBin'])->name('get-pending-bin-dashboard'); 
    Route::get('/dashboard/team-lead-dashboard', [IndexController::class, 'teamLeadDashboard'])->name('team-lead-dashboard');  
    Route::post('/corporate/request/infill', [IndexController::class, 'corporateInfill'])->name('corporate-request-infill'); 
    //All Plans POST Request

    /////////////
    ////////
   
    Route::prefix('/product-request')->group(function(){
        Route::get('/customer-info', [ProductRequestsController::class, 'customerInfo'])->name('customer-info');
        Route::get('/request-profile', [ProductRequestsController::class, 'requestProfile'])->name('request-profile');
        Route::get('/product-checklist', [ProductRequestsController::class, 'productChecklist'])->name('product-checklist');
        Route::get('/attached-documents', [ProductRequestsController::class, 'attachedDocuments'])->name('attached-documents'); 
        Route::get('/slams-logs', [ProductRequestsController::class, 'slamsLogs'])->name('slams-logs');   
        
        Route::post('/save/customer-info', [DocumentApplicationsController::class, 'saveCustomerInfo'])->name('save-customer-info');
        Route::post('/save/product-checklist', [DocumentApplicationsController::class, 'saveCheckListDocuments'])->name('save-checklist-documents');
        Route::post('/save/attached-documents', [DocumentApplicationsController::class, 'saveAttachedDocuments'])->name('save-attached-documents');
        

    });

    Route::prefix('/document')->group( function(){
        Route::get('/claim_request', [ProductRequestsController::class, 'claimRequest'])->name('document-claim-request');
        Route::get('/death_claim', [ProductRequestsController::class, 'deathClaim'])->name('document-death-claim');
        Route::get('/educator', [ProductRequestsController::class, 'educator'])->name('document-educator');
        Route::get('/mandate_request', [ProductRequestsController::class, 'mandateRequest'])->name('document-mandate-request');
        Route::get('/refund_request', [ProductRequestsController::class, 'refundRequest'])->name('document-refund-request');
        Route::get('/personal_accident', [ProductRequestsController::class, 'personalAccident'])->name('document-personal-accident');
        Route::get('/sip', [ProductRequestsController::class, 'sip'])->name('document-sip');
        Route::get('/tpp', [ProductRequestsController::class, 'tpp'])->name('document-tpp');
        Route::get('/transition', [ProductRequestsController::class, 'transition'])->name('document-transition');
        Route::get('/travel_insurance', [ProductRequestsController::class, 'travelInsurance'])->name('document-travel-insurance');
        Route::get('/key_man', [ProductRequestsController::class, 'keyman'])->name('document-keyman');
        Route::get('/corporate', [ProductRequestsController::class, 'corporate'])->name('document-corporate');
        Route::get('/fido_sip', [ProductRequestsController::class, 'fidosip'])->name('document-fidosip');
        
        Route::get('/search-request', [ProductRequestsController::class, 'searchRequest'])->name('document-search-request');
    
        Route::get('/new-product-request', [ProductRequestsController::class, 'customerInfo'])->name('document-my-requests');
        Route::get('/my-requests', [ProductRequestsController::class, 'myRequests'])->name('document-my-requests');
        Route::get('/ussd-requests', [ProductRequestsController::class, 'ussdRequests'])->name('document-ussd-requests');
        Route::get('/slams-requests', [ProductRequestsController::class, 'slamsRequests'])->name('document-slams-requests');
        Route::get('/website-requests', [ProductRequestsController::class, 'websiteRequests'])->name('document-website-requests');
        //
        Route::get('/digital-form', [ProductRequestsController::class, 'digitalForm'])->name('document-digital-form');
        //
        Route::get('/report/all-submissions', [ProductRequestsController::class, 'reportAllSubmissions'])->name('document-all-submissions');
        Route::get('/report/proposals-received', [ProductRequestsController::class, 'reportReceivedProposals'])->name('document-received-proposal-report');
        Route::get('/report/proposals-rejected', [ProductRequestsController::class, 'reportRejectedProposals'])->name('document-rejected-proposal-report');
        Route::get('/report/view', [ProductRequestsController::class, 'viewReport'])->name('document.view-report');
        
        //
        Route::post('/submit/requests', [ProductRequestsController::class, 'submitRequests'])->name('document.submit.requests');
        Route::post('/get/search/results', [ProductRequestsController::class, 'getSearchResults'])->name('document.search-results');
        Route::post('/get/user/requests', [ProductRequestsController::class, 'getUserRequests'])->name('document.user.requests');
        Route::post('/get/slams/requests', [ProductRequestsController::class, 'getSlamsRequests'])->name('document.get-slams.requests');
        Route::post('/get/ussd/requests', [ProductRequestsController::class, 'getUssdRequests'])->name('document.get-ussd.requests');
        Route::post('/get/website/requests', [ProductRequestsController::class, 'getWebsiteRequests'])->name('document.get-website.requests');
       
        Route::post('/search/requests', [ProductRequestsController::class, 'searchRequests'])->name('document.search.requests'); 
        Route::post('/get/custom/search/results', [ProductRequestsController::class, 'customSearchRequests'])->name('document.custom.search-results');

        Route::post('/initiate/submission/report', [ProductRequestsController::class, 'initiateSubmissionReport'])->name('document.initiate.submission.report');
        Route::post('/generate/submission/report', [ProductRequestsController::class, 'generateSubmissionReport'])->name('document.generate.submission.report');
        Route::post('/generate/all-submissions/report', [ProductRequestsController::class, 'getProposalRequestListForReport'])->name('document.all.submissions.requests');

        Route::post('/push-to-slam', [PushToSlamComtroller::class, 'initiatePush'])->name('document.push.to.slam');
        // 
        Route::get('/share/{id}', [ActionMenusController::class, 'sharePage'])->name('action-menu-pages.share');
        Route::post('/share/via-email', [ActionMenusController::class, 'shareSend'])->name('action-menu-pages.share-via-email');

        Route::get('/audit/{id}', [ActionMenusController::class, 'auditPage'])->name('action-menu-pages.audit');
        Route::get('/digital/form-page', [ActionMenusController::class, 'digitalFormPage'])->name('action-menu-pages.share');
        Route::get('/request/overview/{id}', [ActionMenusController::class, 'requestOverview'])->name('action-menu-pages.overview');

        Route::post('/get/multiple/actions/page', [ActionMenusController::class, 'multipleActionsPage'])->name('action-menu-pages.get.multiple-actions.page');
        Route::post('/save/multiple/actions/action', [ActionMenusController::class, 'saveMultipleActionsAction'])->name('action-menu-pages.save.multiple-actions.action');
        
        // Report
        Route::post('/received/proposals/report', [ProductRequestsController::class, 'proposalReceivedReport'])->name('received-proposals-report');
        Route::post('/rejected/proposals/report', [ProductRequestsController::class, 'proposalRejectedReport'])->name('rejected-proposals-report');
        //
        Route::get('/rendering/web-view-share', [ActionMenusController::class, 'shareRender'])->name('action-menu-share.render');
        Route::get('/rendering/tandc-share', [ActionMenusController::class, 'shareTandc'])->name('action-menu-share.tandc');
        //
        Route::get('/view/request', [ActionMenusController::class, 'externalViewRequest'])->name('external.view.request');
        Route::get('/action/request', [ActionMenusController::class, 'externalActionRequest'])->name('external.action.request');

        //
        Route::post('/approve', [ActionMenusController::class, 'approveRequest'])->name('action-menu.approve.request');
        Route::post('/initiate-review', [ActionMenusController::class, 'initiateReview'])->name('action-menu.initiate-review.request');
        Route::post('/review', [ActionMenusController::class, 'reviewRequest'])->name('action-menu.review.request');
        Route::post('/decline', [ActionMenusController::class, 'declineRequest'])->name('action-menu.decline.request');
        Route::post('/delete', [ActionMenusController::class, 'deleteRequest'])->name('action-menu.delete.request');
        Route::post('/override', [ActionMenusController::class, 'overrideStatus'])->name('action-menu.override.status');
        Route::post('/override/save', [ActionMenusController::class, 'overrideStatusSave'])->name('action-menu.override.status.save');

        //
        Route::post('/generate/proposal/form', [ProposalToPDFController::class, 'generatePDF'])->name('document.generate.proposal.pdf');
        Route::post('/generate/mandate/form', [MandateToPDFController::class, 'generatePDF'])->name('document.generate.mandate.pdf');

        //
        Route::post('/delete/attached', [ActionMenusController::class, 'deleteDocument'])->name('action-menu.delete.document');
        
        
        
    });
    Route::prefix('/customer-complaints')->group( function(){
        Route::get('/assigned', [ComplaintsController::class, 'assignedComplaints'])->name('complaints-assigned');
        Route::get('/assigned-admin', [ComplaintsController::class, 'assignedComplaintsAdmin'])->name('complaints-assigned-admin');
        
        Route::post('/search/requests', [ComplaintsController::class, 'initCustomComplaintsSearchRequests'])->name('customer.complaints.init.search'); 
        Route::post('/get/search/results', [ComplaintsController::class, 'getComplaintsSearchResults'])->name('customer.complaints.search-results');
        Route::post('/get/custom/search/results', [ComplaintsController::class, 'getCustomComplaintsSearchRequests'])->name('customer.complaints.custom.search-results');
       
        //
        Route::get('/closed', [ComplaintsController::class, 'closedComplaints'])->name('complaints-closed');
        Route::get('/closed-admin', [ComplaintsController::class, 'closedComplaintsAdmin'])->name('complaints-closed-admin');
        //
        Route::get('/pending', [ComplaintsController::class, 'pendingComplaints'])->name('complaints-pending');
        Route::get('/pending-admin', [ComplaintsController::class, 'pendingComplaintsAdmin'])->name('complaints-pending-admin');
        //
        Route::get('/received', [ComplaintsController::class, 'receivedComplaints'])->name('complaints-received');
        Route::get('/received-admin', [ComplaintsController::class, 'receivedComplaintsAdmin'])->name('complaints-received-admin');
        //
        
        Route::post('/dashboard/menu/init/action', [ComplaintsController::class, 'initAction'])->name('customer.complaints.action.init');
        
        Route::post('/dashboard/menu/action/del-pend', [ComplaintsController::class, 'handleActionDelPend'])->name('customer.complaints.handle.del-pend');
        Route::post('/dashboard/menu/action/share', [ComplaintsController::class, 'handleShareDocument'])->name('customer.complaints.handle.share');
        Route::post('/dashboard/menu/action/assign', [ComplaintsController::class, 'handleAssignComplaint'])->name('customer.complaints.handle.assign');
        Route::post('/dashboard/menu/action/complete', [ComplaintsController::class, 'handleCompleteComplaint'])->name('customer.complaints.handle.complete');
        Route::post('/dashboard/menu/action/update-final', [ComplaintsController::class, 'handleCompleteUpdateFinal'])->name('customer.complaints.handle.update-final');
        

        Route::get('/search', [ComplaintsController::class, 'searchComplaints'])->name('complaints-search');
        Route::get('/search-admin', [ComplaintsController::class, 'searchComplaintsAdmin'])->name('complaints-search-admin');
        Route::get('/register', [ComplaintsController::class, 'registerComplaints'])->name('complaints-register');

        Route::post('/register/save', [ComplaintsController::class, 'registerComplaintSave'])->name('complaints-register-save');
        
        Route::get('/report', [ComplaintsController::class, 'reportComplaints'])->name('complaints-report');
        Route::post('/search/customer/complaints', [ComplaintsController::class, 'searchCustomerComplaints'])->name('search.customer-complaints');
        
    });

    Route::prefix('/super-admin')->group(function(){
        //Menu
        Route::prefix('/menu')->group(function(){
            Route::get('/', [SuperAdministratorController::class, 'listMenu'])->name('list-menu');
            Route::get('/new', [SuperAdministratorController::class, 'newMenu'])->name('new-menu');
            Route::get('/edit/{id}', [SuperAdministratorController::class, 'editMenu'])->name('edit-menu');
            
            Route::post('/ajax/all', [MenuController::class, 'getMenus'])->name('all-menus');
            Route::post('/create', [MenuController::class, 'saveMenu'])->name('create-menu');
            Route::post('/update', [MenuController::class, 'updateMenu'])->name('update-menu');
            Route::post('/delete/{id}', [MenuController::class, 'deleteMenu'])->name('delete-menu');
        });

        //Company profile
        Route::get('/company-profile', [SuperAdministratorController::class, 'companyProfile'])->name('company-profile');
        Route::post('/update/company-profile', [CompanyProfileController::class, 'updateCompanyProfile'])->name('update-company-profile');

        //Email Templates
        Route::get('/email-templates', [SuperAdministratorController::class, 'emailTemplates'])->name('email-templates');
        Route::get('/email-template/{id}', [SuperAdministratorController::class, 'viewEmailTemplate'])->name('view-email-template');
        Route::get('/new/email-template/', [SuperAdministratorController::class, 'newEmailTemplate'])->name('new-email-template');
        Route::get('/view/email-template/{id}', [SuperAdministratorController::class, 'detailsEmailTemplate'])->name('details-email-template');
        Route::get('/email-template/category/{category}', [SuperAdministratorController::class, 'categoryEmailTemplate'])->name('category-email-template');

        Route::post('/create/email-template/', [EmailTemplateController::class, 'createEmailTemplate'])->name('create-email-template');
        Route::post('/update/email-template/', [EmailTemplateController::class, 'updateEmailTemplate'])->name('update-email-template');
        //Application Status
        Route::get('/application-status', [SuperAdministratorController::class, 'applicationStatus'])->name('application-status');
        //
        Route::post('/create/action-module/', [ApplicationStatusController::class, 'createActionModule'])->name('create-action-module');
        Route::post('/update/action-module/', [ApplicationStatusController::class, 'updateActionModule'])->name('update-action-module');
        Route::post('/save/update/job/approval/limit', [ApplicationStatusController::class, 'updateJobApprovalLimit'])->name('update-save-job-approval-limit');
        //
        Route::post('/create/action-submodule/', [ApplicationStatusController::class, 'createActionSubModule'])->name('create-action-submodule');
        Route::post('/update/action-submodule/', [ApplicationStatusController::class, 'updateActionSubModule'])->name('update-action-submodule');
        
        Route::post('/save/update/action-submodule/details', [ApplicationStatusController::class, 'saveUpdateActionSubModuleDetails'])->name('save-update-job-submodule-details');

        Route::post('/view/action-submodule/{module}/{sub_module}', [ApplicationStatusController::class, 'subModuleDetails'])->name('view-action-submodule');
        //Endpoint
        Route::post('/view/endpoint/details/{module}/{sub_module}/{endpoint}', [ApplicationStatusController::class, 'endpointDetails'])->name('view-endpoint-details');
        
        Route::post('/save/update/endpoint/details', [ApplicationStatusController::class, 'saveUpdateEndpointDetails'])->name('save-update-job-submodule-details');
        

        
        


    });
    
    Route::prefix('/admin')->group(function(){
        Route::prefix('/complaints')->group( function(){
            Route::get('/manage-types', [AdministratorController::class, 'complaintsType'])->name('admin-complaints-type');
            Route::post('/types/save', [ComplaintTypeController::class, 'saveComplaintsType'])->name('save-complaints-type');
            Route::post('/types/update', [ComplaintTypeController::class, 'updateComplaintsType'])->name('update-complaints-type');
            Route::post('/types/delete', [ComplaintTypeController::class, 'deleteComplaintsType'])->name('delete-complaints-type');
            
        });
        Route::prefix('/users')->group( function(){
            Route::get('/list', [AdministratorController::class, 'listUsers'])->name('admin-users-list');
            Route::get('/new', [AdministratorController::class, 'createUpdateUser'])->name('admin-create-update-user');
            Route::get('/manage-profiles', [AdministratorController::class, 'manageProfile'])->name('admin-manageProfile');
            Route::get('/main-category', [AdministratorController::class, 'mainCategory'])->name('admin-user-main-category');
            Route::post('/main-category/save', [UserCategoryController::class, 'saveMainCategory'])->name('admin-user-save-main-category');
            Route::post('/main-category/update', [UserCategoryController::class, 'updateMainCategory'])->name('admin-user-update-main-category');
            Route::post('/main-category/delete/{id}', [UserCategoryController::class, 'softdeleteMainCategory'])->name('admin-soft-delete-main-category');
            Route::get('/sub-category', [AdministratorController::class, 'subCategory'])->name('admin-user-subCategory');
            Route::post('/sub-category/save', [UserSubCategoryController::class, 'saveSubCategory'])->name('admin-user-save-subCategory');
            Route::post('/sub-category/update', [UserSubCategoryController::class, 'updateSubCategory'])->name('admin-user-update-subCategory');
            Route::post('/sub-category/delete/{id}', [UserSubCategoryController::class, 'softdeleteSubCategory'])->name('admin-soft-delete-main-category');
            Route::get('/partners/list', [AdministratorController::class, 'listPartners'])->name('admin-list-partners');
            Route::post('/partners/save', [OrganisationUnitController::class, 'savePartner'])->name('admin-save-partner');
            Route::post('/partners/update', [OrganisationUnitController::class, 'updatePartner'])->name('admin-update-partner');
            Route::post('/partners/delete/{id}', [OrganisationUnitController::class, 'softdeletePartner'])->name('admin-delete-partner');
            Route::get('/partners/sub/category/{id}', [OrganisationUnitController::class, 'getPartnerSubCategory'])->name('admin-get-partner-subcategory');

            // Route::get('/new/partners', [AdministratorController::class, 'newUser'])->name('admin-new-partner');

        });
        Route::prefix('/departments')->group( function(){
            Route::get('/', [AdministratorController::class, 'listDepartments'])->name('admin-department-list');
            Route::post('/save', [DepartmentController::class, 'createDepartment'])->name('admin-create-department');
            Route::post('/update', [DepartmentController::class, 'updateDepartment'])->name('admin-update-department');
            Route::post('/delete/{id}', [DepartmentController::class, 'softdeleteDepartment'])->name('admin-softdelete-department');
        });
        Route::prefix('/branches')->group( function(){
            Route::get('/list', [AdministratorController::class, 'listBranches'])->name('admin-users-list');
            Route::post('/save', [BranchesController::class, 'saveBranch'])->name('admin-save-branch');
            Route::post('/update', [BranchesController::class, 'updateBranch'])->name('admin-update-branch');
            Route::post('/delete/{id}', [BranchesController::class, 'deleteBranch'])->name('admin-delete-branch');
        });
        Route::prefix('/documents')->group( function(){
            Route::get('/manage-products', [AdministratorController::class, 'documentManageProduct'])->name('admin-document-manage-products');
            Route::get('/new-product', [AdministratorController::class, 'documentNewProduct'])->name('admin-document-new-product');
            Route::get('/view-product', [AdministratorController::class, 'documentViewProduct'])->name('admin-document-view-product');
            Route::post('/create/update', [DocumentProductsController::class, 'documentCreateUpdateProduct'])->name('admin-document-creatte-update-product');
            Route::get('/types', [AdministratorController::class, 'documentType'])->name('admin-document-type');
            Route::post('/types/save', [DocumentTypeController::class, 'saveDocumentType'])->name('admin-save-document-type');
            Route::post('/types/update', [DocumentTypeController::class, 'updateDocumentType'])->name('admin-update-document-type');
            Route::post('/types/delete/{id}', [DocumentTypeController::class, 'softdeleteDocumentType'])->name('admin-delete-document-type');
            
            Route::get('/restriction', [AdministratorController::class, 'documentRestriction'])->name('admin-document-restriction');
            Route::post('/restriction/save', [RestrictionController::class, 'saveRestriction'])->name('admin-save-document-restriction');
            Route::post('/restriction/update', [RestrictionController::class, 'updateRestriction'])->name('admin-update-document-restriction');
            Route::post('/restriction/delete/{id}', [RestrictionController::class, 'softdeleteRestriction'])->name('admin-delete-document-restriction');

            Route::get('/workflow-setup', [AdministratorController::class, 'documentWorkflow'])->name('admin-document-workflow-setup');
            Route::get('/workflow-setup-details/{workflow}', [AdministratorController::class, 'documentWorkflowDetails'])->name('admin-document-workflow-setup-details');
            Route::post('/save/update/workflow-setup-details', [DocumentSetupController::class, 'saveUpdateDocumentSetup'])->name('admin-saveUpdate-setup-details');
            Route::post('/save/workflow', [DocumentSetupController::class, 'newWorkflow'])->name('admin-document-new-workflow');
            Route::get('/product', [AdministratorController::class, 'documentProduct'])->name('admin-document-product');

        });
        Route::prefix('/dashboard')->group( function(){
            Route::get('/set-goals', [AdministratorController::class, 'setGoals'])->name('admin-set-goal');
        });
        Route::prefix('/finance')->group( function(){
            Route::get('/set-set-goals', [AdministratorController::class, 'seeeGoals'])->name('admin-set-goal');
        });
        Route::prefix('/inventory')->group( function(){
            Route::get('/set-requisition', [AdministratorController::class, 'setRequisition'])->name('admin-set-requisition');
            Route::get('/expiry-notification', [AdministratorController::class, 'expiryNotification'])->name('admin-expiry-notification');
        });

        Route::post('/user/switch/profile', [AdministratorController::class, 'switchProfile'])->name('admin.switch.profile');
        
    });
});

//All Plans POST Requests
//EDUCATOR PLAN
Route::post('/save/update/educator/request', [EducatorsController::class, 'saveOrUpdate'])->name('save-update-educator-request'); 
//DEATH CLAIM
Route::post('/save/update/deathclaim/request', [DeathClaimsController::class, 'saveOrUpdate'])->name('save-update-deathclaim-request'); 
//CLAIM REQUEST
Route::post('/save/update/claim/request', [ClaimRequestsController::class, 'saveOrUpdate'])->name('save-update-claim-request'); 
//TERM ASSURANCE
Route::post('/save/update/termassurance/request', [TermAssurancesController::class, 'saveOrUpdate'])->name('save-update-termassurance-request'); 
//MANDATE REQUEST
Route::post('/save/update/mandate/request', [MandateRequestsController::class, 'saveOrUpdate'])->name('save-update-mandate-request'); 
//PERSONAL ACCIDENT
Route::post('/save/update/personalaccident/request', [PersonalAccidentsController::class, 'saveOrUpdate'])->name('save-update-personalaccident-request'); 
//REFUND REQUEST
Route::post('/save/update/refund/request', [RefundRequestsController::class, 'saveOrUpdate'])->name('save-update-refund-request'); 
//SPECIAL INVESTMENT PLAN
Route::post('/save/update/specialinsvestment/request', [SipsController::class, 'saveOrUpdate'])->name('save-update-specialinsvestment-request'); 
//TRANSITION PLUS PLAN
Route::post('/save/update/transitionplus/request', [TppsController::class, 'saveOrUpdate'])->name('save-update-transitionplus-request'); 
//TRANSITION
Route::post('/save/update/transition/request', [TransitionsController::class, 'saveOrUpdate'])->name('save-update-transition-request'); 
//TRAVEL INSURANCE
Route::post('/save/update/travelinsurance/request', [TravelInsurancesController::class, 'saveOrUpdate'])->name('save-update-travelinsurance-request'); 
//KEY MAN
Route::post('/save/update/keyman/request', [KeymansController::class, 'saveOrUpdate'])->name('save-update-keyman-request'); 
//FIDO SIP
Route::post('/save/update/fidosip/request', [FidoSipsController::class, 'saveOrUpdate'])->name('save-update-fidosip-request'); 
//CORPORATE DUE DILIGENCE 
Route::post('/save/update/corporate/request', [CorporateDueDiligencesController::class, 'saveOrUpdate'])->name('save-update-corporate-request'); 


// 
Route::get('/document/external/view/proposal', [DocumentPreviewController::class, 'viewProposal'])->name('external-view-document-proposal');
Route::get('/document/external/view/mandate', [DocumentPreviewController::class, 'viewMandate'])->name('external-view-document-mandate');
Route::get('/document/external/fill/proposal', [DocumentPreviewController::class, 'fillProposal'])->name('external-fill-document-proposal');
Route::get('/document/external/fill/mandate', [DocumentPreviewController::class, 'fillMandate'])->name('external-fill-document-mandate');
//
Route::get('/document/external/terms-and-conditions', [DocumentPreviewController::class, 'termsAndConditions'])->name('external-terms-and-conditions');

Route::post('/document/product/request/verify/phone-number', [verificationController::class, 'verifyPhoneNumber'])->name('document-product-request.verify-phone-number');
Route::post('/document/product/request/verify/id-number', [verificationController::class, 'verifyIdNumber'])->name('document-product-request.verify-id-number');
Route::post('/document/product/request/id/upload', [verificationController::class, 'uploadId'])->name('document-product-request.upload-id');
//
Route::post('/document/product/request/verify/acc-number', [verificationController::class, 'verifyAccNumber'])->name('document-product-request.verify-acc-number');
//
Route::get('/document/ajax_calls/dependent_dropdowns', [DependentDropdownController::class, 'getDropdown'])->name('dependent-dropdown'); 
///document/generate/mandate/proposal/forms
Route::post('/document/generate/mandate/proposal/forms', [ProposalAndMandateToPDFController::class, 'generateBoth'])->name('document.generate.mandate.pdf');
//
Route::get('/document/preview-proposal/{token}', [DocumentPreviewController::class, 'previewProposal'])->name('document-preview-proposal');
Route::get('/document/preview-mandate/{token}', [DocumentPreviewController::class, 'previewMandate'])->name('document-preview-mandate');
//  document/request-camera-document //document/request-document
Route::get('/document/action/request', [DocumentPreviewController::class, 'ationFromEmail'])->name('document-action-from-email');
//
Route::get('/document/view/request', [DocumentPreviewController::class, 'viewFromEmail'])->name('document-view-from-email');

Route::post('/document/action/request/action', [DocumentPreviewController::class, 'emailAction'])->name('document-action-action');

Route::post('/document/product/request/flag', [verificationController::class, 'flagRequest'])->name('document-action-action');

Route::get('/customer-complaint/web-api', [ComplaintsController::class, 'webApi'])->name('customer-complaint.web-api');
Route::get('/customer-complaint/view-complaint', [ComplaintsController::class, 'viewComplaint'])->name('customer-complaint.view-complaint');


// Route::group(['prefix'=>'user','middleware' => ['user','auth'],'namespace'=>'User'],function(){});


