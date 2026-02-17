<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- CONTROLLERS ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Announcement;
use App\Models\BarangayOfficials;
use App\Models\DocumentRequest;
use App\Models\Complaint; 
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\DocumentRequestController;
use App\Http\Controllers\User\ComplaintController;
use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ResidentManagementController;
use App\Http\Controllers\Admin\DocumentManagementController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\BrgyOfficialsController;
use App\Http\Controllers\Admin\AdminProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================================================================
// 1. PUBLIC ROUTES
// ========================================================================
Route::get('/', function () {
    return view('landing', [
        'announcements' => Announcement::where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest()
                ->take(6)
            ->get(),
        'officials' => BarangayOfficials::all()
    ]);
})->name('landing');

Route::get('/privacy-policy', function () { 
    return view('pages.privacy-policy'); 
})->name('privacy.policy');

// ========================================================================
// 2. AUTHENTICATION
// ========================================================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post'); 
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========================================================================
// 3. REGISTRATION
// ========================================================================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register/step1', [RegisterController::class, 'processStep1'])->name('register.step1.submit');
Route::get('/register/back', [RegisterController::class, 'backToStep1'])->name('register.back');
Route::post('/register/submit', [RegisterController::class, 'register'])->name('register.submit');

// ========================================================================
// 4. RESIDENT ROUTES (Logged-in Users)
// ========================================================================
Route::middleware(['auth'])->group(function () {

    // Homepage / Dashboard
    Route::get('/home', function () {
        $user = Auth::user();

        // 1. Fetch Document Requests
        $documents = DocumentRequest::where('resident_id', $user->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 2. Fetch Complaints
        $complaints = Complaint::where('user_id', $user->id)
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 3. Merge and Sort
        $recentActivities = $documents->concat($complaints)->sortByDesc('updated_at')->take(5);

        return view('user.homepage.index', compact('user', 'recentActivities'));
    })->name('home');

    // Redirection fallback
    Route::get('/user-homepage', function () { 
        return redirect()->route('home'); 
    });

    // Profile Management
    Route::get('/user-profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::put('/user-profile', [UserProfileController::class, 'update'])->name('user.profile.update');

    // Document Requests
    Route::get('/user-document-request', [DocumentRequestController::class, 'index'])
        ->name('user.document-requests.index');
    Route::post('/user/document-request', [DocumentRequestController::class, 'store'])
        ->name('user.document.store');

    // Complaints (User Side)
    Route::get('/user-complaint', [ComplaintController::class, 'index'])
        ->name('user.complaints.index');
    Route::post('/user-complaint', [ComplaintController::class, 'store'])
        ->name('user.complaints.store');

    // 🛑 REMOVED OLD NOTIFICATION ROUTES (Conflict with new API)
    // Route::get('/notifications', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.getUnread');
    // Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    
    // 🚀 CRITICAL FIX: NEW NOTIFICATION API ENDPOINTS
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications.index');
    Route::patch('/api/notifications/{id}', [NotificationController::class, 'markAsRead'])->name('api.notifications.markAsRead');
});

// ========================================================================
// 5. ADMIN ROUTES
// ========================================================================
Route::middleware(['auth:admin', 'check.admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- USERS MANAGEMENT ---
    Route::get('/users', [ResidentManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [ResidentManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [ResidentManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [ResidentManagementController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/accept', [ResidentManagementController::class, 'accept'])->name('users.accept')->withTrashed();
    Route::put('/users/{user}/approve', [ResidentManagementController::class, 'approve'])->name('users.approve')->withTrashed();
    Route::put('/users/{user}/reject', [ResidentManagementController::class, 'reject'])->name('users.reject');
    Route::put('/users/{user}/archive', [ResidentManagementController::class, 'archive'])->name('users.archive');
    Route::put('/users/{user}/restore', [ResidentManagementController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{user}', [ResidentManagementController::class, 'destroy'])->name('users.destroy');

    // --- DOCUMENT REQUESTS ---
    Route::get('/documents', [DocumentManagementController::class, 'showDocumentsPage'])->name('documents.index');
    Route::get('/documents/request/{id}', [DocumentManagementController::class, 'getDocumentRequest'])->name('documents.get');
    Route::put('/documents/{id}/status', [DocumentManagementController::class, 'updateStatus'])->name('documents.updateStatus');

    // --- ANNOUNCEMENTS ---
    Route::resource('announcements', AnnouncementController::class);

    // --- COMPLAINTS ---
    Route::resource('complaints', AdminComplaintController::class)->only(['index', 'show', 'update']);

    // --- STAFFS ---
    Route::resource('staffs', StaffController::class)->only(['index', 'create', 'store', 'show', 'update']);
    Route::patch('staffs/{staff}/deactivate', [StaffController::class, 'deactivate'])->name('staffs.deactivate');

    // --- BARANGAY OFFICIALS ---
    Route::resource('brgy-officials', BrgyOfficialsController::class, ['names' => 'brgyOfficials'])->only(['index', 'store', 'update', 'destroy']);

    // --- REPORTS ---
    Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index')->middleware(\App\Http\Middleware\CheckAdmin::class);
    Route::post('/reports/export', [App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('reports.export')->middleware(\App\Http\Middleware\CheckAdmin::class);

    // --- AUDIT LOGS ---
    Route::get('/auditlogs', [App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('auditlogs.index');

    // --- ADMIN PROFILE ---
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');

    // --- ADMIN NOTIFICATION API ENDPOINTS ---
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications.index');
    Route::patch('/api/notifications/{id}', [NotificationController::class, 'markAsRead'])->name('api.notifications.markAsRead');
});