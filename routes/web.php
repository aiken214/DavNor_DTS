<?php

use App\Http\Controllers\MydashboardController;
use App\Http\Controllers\MyProfilePageController;
use App\Http\Controllers\GuestDtsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AllUsersController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Dts\DocumentController;
use App\Http\Controllers\Dts\IncomingDocController;
use App\Http\Controllers\Dts\DeferredDocController;
use App\Http\Controllers\Dts\ReceivedDocController;
use App\Http\Controllers\Dts\ForwardedDocController;
use App\Http\Controllers\Dts\DocTypeController;
use App\Http\Controllers\Dts\GuestDocController;
use App\Http\Controllers\Dts\DtsDocRouteController;
use App\Http\Controllers\Dts\BatchReleaseController;
use App\Http\Controllers\Dts\BarcodePrintController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Dts\QrCodeReceiptController;
use App\Http\Controllers\Dts\MyDocumentController;
use App\Http\Controllers\Dts\MyStationController;
use App\Http\Controllers\Dts\ParkedRoutesController;
use App\Http\Controllers\Dts\KeptDocumentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/sign-in', function () {
    return view('sign-in');
})->name('login');

Route::get('/guest-dts', [GuestDtsController::class, 'createGuestDocument'])->name('guest-dts');
Route::get('/user-by-section/{sectionId}', [GuestDtsController::class, 'getUserBySecId']);
Route::post('/save-document', [GuestDtsController::class, 'storeGuestDocument'])->name('guest-document-store');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [MydashboardController::class, 'index'])->name('dashboard');
    Route::post('/user/update-station', [UserController::class, 'updateStation'])->name('user.updateStation');
   
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function(){
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
   
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles-edit/{id}', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles-store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('users-create', [UserController::class, 'create'])->name('users.create');
    Route::get('users', [UserController::class, 'index'])->name('users.index');  
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');   
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    //sections
    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('/sections-create', [SectionController::class, 'create'])->name('sections-create');
    Route::get('/sections-edit/{dtsSection}', [SectionController::class, 'edit'])->name('sections-edit');
    Route::post('/sections-store', [SectionController::class, 'store'])->name('sections.store');
    Route::post('/section-update', [SectionController::class, 'updateSection'])->name('sections.update');
    Route::post('/section-delete', [SectionController::class, 'destroy'])->name('sections.destroy');
    // System Seetings
    Route::post('/system-park-routes', [SystemSettingController::class, 'parkRoutes'])->name('park-routes');
    Route::get('/system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
    Route::get('/system-settings/{dtsSystemSetting}/edit', [SystemSettingController::class, 'edit'])->name('system-settings.edit');
    Route::patch('/system-settings/{dtsSystemSetting}', [SystemSettingController::class, 'update'])->name('system-settings.update');
   
   
    
});

Route::group(['prefix' => 'dts', 'as' => 'dts.', 'namespace' => 'Dts', 'middleware' => ['auth']], function(){
    Route::get('/dashboard', [DocumentController::class, 'index'])->name('dashboard');
    Route::post('/search', [DocumentController::class, 'search'])->name('search');
    Route::get('/search', [DocumentController::class, 'searchResults'])->name('dts.search.results');

    Route::get('/guest-doc', [GuestDocController::class, 'index'])->name('guest-doc');
    Route::post('/guest-doc/accept', [GuestDocController::class, 'acceptGuestDoc'])->name('guest-doc.accept');
    Route::get('/section-stat',[DocumentController::class, 'sectionStat'])->name('section-stat');
    Route::get('/documents/create-forward', [DocumentController::class, 'createForward'])->name('documents.create-forward');
    Route::post('/guest-doc-destroy', [GuestDocController::class, 'destroy'])->name('guest-doc-destroy');
    // documenet updated
    Route::post('/newdocument-update', [DocumentController::class, 'updateNewDocument'])->name('newdocument-update');
    Route::post('/document-update', [DocumentController::class, 'updateDocument'])->name('document-update');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents-show-new-created/{docId}/{routeId}', [DocumentController::class, 'showNewCreated'])->name('show-new-created');
    Route::get('/documents/{dtsDocument}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/document-view/{docId}', [DocumentController::class, 'docView'])->name('document-view');
    Route::get('/documents/{dtsDocument}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
    Route::patch('/documents/{dtsDocument}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('/documents/{dtsDocument}', [DocumentController::class, 'destroy'])->name('documents.destroy');    
    Route::get('/get-users-by-section/{id}', [DocumentController::class, 'getUsersBySection']);

    Route::get('/incoming-docs', [IncomingDocController::class, 'index'])->name('incoming-docs.index');
    Route::post('/incoming-docs/accept', [IncomingDocController::class, 'acceptDoc'])->name('incomingdoc-accept');
    Route::post('/incoming-docs/accept-andfile', [IncomingDocController::class, 'acceptAndFileDoc'])->name('incomingdoc-accept-andfile');
   

    //QR COde search/receipt
    Route::post('/qrcode-search', [QrCodeReceiptController::class, 'searchResult'])->name('qrcode-search');
    Route::post('/quick-receipt', [QrCodeReceiptController::class, 'quickReceipt'])->name('quick-receipt');
    Route::get('/webcam-qr-scan', [QrCodeReceiptController::class, 'webcamScan'])->name('webcam-qr-scan');
    Route::get('/qr-search', [QrCodeReceiptController::class, 'qrSearch'])->name('qr-search');
   

    Route::get('/forwarded-docs', [ForwardedDocController::class, 'index'])->name('forwarded-docs.index');
    Route::post('/forwarded-docs/update-forwarded-doc', [ForwardedDocController::class, 'updateForwardedDoc'])->name('forwarded-docs.update-forwarded-doc');
    Route::post('/forwarded-docs/cancel-forwarded-doc', [ForwardedDocController::class, 'cancelForwardedDoc'])->name('forwarded-docs.cancel-forwarded-doc');

    Route::get('/received-docs', [ReceivedDocController::class, 'index'])->name('received-docs.index');
    Route::post('/received-docs/forwarding-the-document',[ReceivedDocController::class, 'forwardDoc'])->name('received-docs.forwarding-the-document');
    Route::post('/received-docs/file-kept',[ReceivedDocController::class, 'fileKept'])->name('received-docs.file-kept');
    Route::post('/received-docs/file-released',[ReceivedDocController::class, 'fileReleased'])->name('received-docs.file-released');
    Route::post('/received-docs/deferred-doc',[ReceivedDocController::class, 'deferredDoc'])->name('received-docs.deferred-doc');
    
    Route::get('/system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
    Route::get('/system-settings/{dtsSystemSetting}/edit', [SystemSettingController::class, 'edit'])->name('system-settings.edit');
    Route::patch('/system-settings/{dtsSystemSetting}', [SystemSettingController::class, 'update'])->name('system-settings.update');
    // Additional DocTypes can be added here
    Route::get('/doc-types', [DocTypeController::class, 'index'])->name('doc-types.index');
    Route::get('/doc-types/{dtsDocTypes}/edit', [DocTypeController::class, 'edit'])->name('doc-types.edit');
    Route::post('/doc-types-update', [DocTypeController::class, 'update'])->name('doc-types.update');
    Route::post('/doc-types-store', [DocTypeController::class, 'store'])->name('doc-types.store');
    Route::get('/doc-types-create', [DocTypeController::class, 'create'])->name('doc-types.create');
    Route::delete('/doc-types/{dtsDocTypes}', [DocTypeController::class, 'destroy'])->name('doc-types.destroy');
    //------DefferedDocs --
    Route::get('/deferred-docs',[DeferredDocController::class,'index'])->name('deferred-docs.index');
    Route::post('/deferred-docs/forwarding-the-document',[DeferredDocController::class, 'forwardDoc'])->name('deferred-docs.forwarding-the-document');
    Route::post('/deferred-docs/file-kept',[DeferredDocController::class, 'fileKept'])->name('deferred-docs.file-kept');
    Route::post('/deferred-docs/file-released',[DeferredDocController::class, 'fileReleased'])->name('deferred-docs.file-released');
    // --- batch release
    Route::post('/batch-releases-add-item', [BatchReleaseController::class, 'addOneItemforRelease'])->name('batch-releases-add-item');
    Route::get('/batch-releases-for-print-view/{docId}', [BatchReleaseController::class, 'forPrintView'])->name('batch-releases-for-print-view');
    Route::post('/batch-releases-remove-item', [BatchReleaseController::class, 'removeOneItemforRelease'])->name('batch-releases-remove-item');
    Route::post('/batch-releases-release-docs', [BatchReleaseController::class, 'releaseDocs'])->name('batch-releases-release-docs');
    Route::post('/batch-releases-store', [BatchReleaseController::class, 'store'])->name('batch-releases.store');
    Route::get('/batch-releases', [BatchReleaseController::class, 'index'])->name('batch-releases.index');
    Route::get('/batch-releases-show/{dtsBatchRelease}', [BatchReleaseController::class, 'show'])->name('batch-releases.show');
    //-- Barcode Printing
    Route::get('/barcode-slip-print/{docId}', [BarcodePrintController::class, 'printSlip'])->name('barcode-slip-print');
    Route::get('/barcode-top-right-print/{docId}', [BarcodePrintController::class, 'printTopRight'])->name('barcode-top-right-print');
    Route::get('/barcode-bottom-right-print/{docId}', [BarcodePrintController::class, 'printBottomRight'])->name('barcode-bottom-right-print');
    Route::get('/barcode-bottom-left-print/{docId}', [BarcodePrintController::class, 'printBottomLeft'])->name('barcode-bottom-left-print');

    // MyDocumentController
    Route::get('/my-documents', [MyDocumentController::class, 'index'])->name('my-documents');
    Route::get('/my-document-view/{docId}', [MyDocumentController::class, 'viewMyDoc'])->name('my-documents-view');
    Route::get('/my-print-slip/{docId}', [MyDocumentController::class, 'myPrintSlip'])->name('my-print-slip');
    Route::get('/my-print-top-right/{docId}', [MyDocumentController::class, 'myPrintTopRight'])->name('my-print-top-right');
    Route::get('/my-print-bottom-right/{docId}', [MyDocumentController::class, 'myPrintBottomRight'])->name('my-print-bottom-right');
    Route::get('/my-print-bottom-left/{docId}', [MyDocumentController::class, 'myPrintBottomLeft'])->name('my-print-bottom-left');

    


    //MyStationController
    Route::get('/my-section', [MyStationController::class, 'index'])->name('my-station');
    Route::post('/my-section/query-dates', [MyStationController::class, 'queryDates'])->name('my-station.query-dates');
    Route::get('/my-section/clear-dates', [MyStationController::class, 'clearDates'])->name('my-station.clear-dates');
    Route::get('/my-section-received-docs', [MyStationController::class, 'viewReceivedDocuments'])->name('my-section-received');
    Route::get('/my-section-forwarded-docs', [MyStationController::class, 'viewForwardedDocs'])->name('my-section-forwarded');
    Route::get('/my-section-kept-docs', [MyStationController::class, 'viewDocumentKept'])->name('my-section-kept');
    Route::get('/mysection-pending-documents', [MyStationController::class, 'viewPendingDocuments'])->name('mysection-pending-documents');

    // Parked Documents
    Route::get('/incoming-parked', [ParkedRoutesController::class, 'incomingParked'])->name('incoming-parked');
    Route::get('/pending-parked', [ParkedRoutesController::class, 'pendingParked'])->name('pending-parked');
    Route::get('/deffered-parked', [ParkedRoutesController::class, 'deferredParked'])->name('deffered-parked');
    //kept documents
    Route::get('/kept-documents', [KeptDocumentController::class, 'receivedKept'])->name('kept-documents');
    
});

require __DIR__.'/auth.php';
