<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\BloodCollectionController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\BloodTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;

// ─── ROOT ─────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    return redirect()->route('login');
});

// ─── GUEST ROUTES (unauthenticated) ───────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('login',    [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login',   [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register',[AuthController::class, 'register']);
});

// ─── LOGOUT ───────────────────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── SETUP ADMIN (first run only — protect in production) ────────────────────
Route::get('/setup-admin', function () {
    $admin = \App\Models\User::firstOrCreate(
        ['email' => 'admin@bloodbank.com'],
        [
            'name'     => 'System Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role'     => 'admin',
        ]
    );
    \Illuminate\Support\Facades\Auth::login($admin);
    return redirect()->route('dashboard')->with('success', 'Admin account ready!');
});

// ─── AUTHENTICATED ROUTES ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard (all roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Settings (all roles) ──────────────────────────────────────────────────
    Route::get('settings',                  [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/profile',          [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('settings/password',         [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');

    // ── Notifications (all roles) ─────────────────────────────────────────────
    Route::get('notifications',                         [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read',    [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('notifications/mark-all-read',          [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::delete('notifications/{notification}',       [NotificationController::class, 'destroy'])->name('notifications.destroy');


    // ─────────────────────────────────────────────────────────────────────────
    // Blood Collections – IMPORTANT: static routes MUST be declared before
    // any wildcard {blood_collection} route, otherwise Laravel will eat
    // /blood-collections/create as a model ID and return 404.
    // ─────────────────────────────────────────────────────────────────────────

    // Step 1: Admin + Staff explicit static routes (create, store) – NO wildcard here
    Route::middleware('role:admin,staff')->group(function () {
        Route::get( 'blood-collections/create', [BloodCollectionController::class, 'create'])->name('blood-collections.create');
        Route::post('blood-collections',        [BloodCollectionController::class, 'store'])->name('blood-collections.store');
        Route::get( 'blood-collections/{blood_collection}/edit',   [BloodCollectionController::class, 'edit'])->name('blood-collections.edit');
        Route::put( 'blood-collections/{blood_collection}',        [BloodCollectionController::class, 'update'])->name('blood-collections.update');
        Route::delete('blood-collections/{blood_collection}',      [BloodCollectionController::class, 'destroy'])->name('blood-collections.destroy');
        Route::post('blood-collections/{collection}/screening',    [BloodCollectionController::class, 'updateScreening'])->name('blood-collections.updateScreening');
    });

    // Step 2: Admin + Staff + Donor shared routes (index, show) – wildcard registered AFTER static routes above
    Route::middleware('role:admin,staff,donor')->group(function () {
        Route::resource('donors', DonorController::class);

        // Appointments – Donor/Staff/Admin only (Hospitals excluded)
        Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
        Route::post('appointments/{appointment}/status', [App\Http\Controllers\AppointmentController::class, 'updateStatus'])
            ->name('appointments.updateStatus');

        // Blood Collections: index (safe – no wildcard) then show (wildcard – registered LAST)
        Route::get('blood-collections', [BloodCollectionController::class, 'index'])->name('blood-collections.index');
        Route::get('blood-collections/{blood_collection}', [BloodCollectionController::class, 'show'])->name('blood-collections.show');
    });

    // ── Admin + Staff + Hospital ──────────────────────────────────────────────
    Route::middleware('role:admin,staff,hospital')->group(function () {
        Route::resource('hospitals',      HospitalController::class);
        Route::resource('blood-requests', BloodRequestController::class);

        // Status update (approve / reject)
        Route::post('blood-requests/{blood_request}/status',
            [BloodRequestController::class, 'updateStatus']
        )->name('blood-requests.updateStatus');

        // Blood Inventory Read-only View
        Route::get('blood-inventory/view', [BloodInventoryController::class, 'readOnlyIndex'])->name('blood-inventory.view');
    });

    // ── Admin + Staff only ────────────────────────────────────────────────────
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('blood-inventory', BloodInventoryController::class);
        
        Route::get('blood-tests/dashboard', [BloodTestController::class, 'dashboard'])->name('blood-tests.dashboard');
        Route::resource('blood-tests',     BloodTestController::class);
        
        Route::resource('distributions',   DistributionController::class);

        // Reports
        Route::get('reports',        [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/print',  [ReportController::class, 'print'])->name('reports.print');
    });

    // ── Admin only ────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
    });

});
