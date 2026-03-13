<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParoisseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\RevenueCategoryController;
use App\Http\Controllers\RevenueTypeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SacramentController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\InventaireMagasinController;
use App\Http\Controllers\InventairePatrimoineController;

// Routes publiques
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur (Mon compte)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Configuration
    Route::match(['put', 'post'], 'configurations/bulk', [ConfigurationController::class, 'updateBulk'])
        ->name('configurations.update-bulk');
    Route::resource('configurations', ConfigurationController::class);

    // Utilisateurs (nécessite permission manage_users)
    Route::resource('users', UserController::class)
        ->middleware('permission:manage_users');

    // Paroisses (nécessite permission manage_paroisses)
    Route::resource('paroisses', ParoisseController::class)
        ->parameters(['paroisses' => 'paroisse'])
        ->middleware('permission:manage_paroisses');

    // Membres (permissions granulaires via MemberController)
    Route::resource('members', MemberController::class);

    // Événements (permissions granulaires via EventController)
    Route::resource('events', EventController::class);

    // Sacrements (Baptêmes, Confirmations, Communions, Mariages, Obsèques)
    Route::resource('sacraments', SacramentController::class);

    // API sync pour mode offline (recettes et dépenses)
    Route::post('api/sync', [SyncController::class, 'store'])->name('api.sync');

    // Finances - Recettes
    Route::resource('revenues', RevenueController::class);

    // Finances - Catégories et types de recettes (par paroisse)
    Route::resource('revenue-categories', RevenueCategoryController::class)->except(['show']);
    Route::resource('revenue-types', RevenueTypeController::class)->except(['show']);

    // Finances - Dépenses
    Route::resource('expenses', ExpenseController::class);

    // Inventaire (produits alimentaires et patrimoine)
    Route::resource('inventaire-magasin', InventaireMagasinController::class)->except(['show']);
    Route::resource('inventaire-patrimoine', InventairePatrimoineController::class)->except(['show']);

    // Finances - Rapports financiers
    // Routes spécifiques AVANT les routes avec paramètres pour éviter les conflits
    Route::get('financial-reports', [FinancialReportController::class, 'index'])->name('financial-reports.index');
    Route::get('financial-reports/list', [FinancialReportController::class, 'list'])->name('financial-reports.list');
    Route::get('financial-reports/statistics', [FinancialReportController::class, 'statistics'])->name('financial-reports.statistics');
    Route::get('financial-reports/revenues-weekly', [FinancialReportController::class, 'revenuesWeekly'])->name('financial-reports.revenues-weekly');
    Route::get('financial-reports/revenues-weekly/print', [FinancialReportController::class, 'revenuesWeeklyPrint'])->name('financial-reports.revenues-weekly-print');
    Route::post('financial-reports/revenues-weekly/pdf', [FinancialReportController::class, 'downloadRevenuesWeeklyPdf'])->name('financial-reports.revenues-weekly-pdf');
    Route::get('financial-reports/popote', [FinancialReportController::class, 'popoteReport'])->name('financial-reports.popote');
    Route::get('financial-reports/popote/print', [FinancialReportController::class, 'popotePrint'])->name('financial-reports.popote-print');
    Route::post('financial-reports/popote/pdf', [FinancialReportController::class, 'downloadPopotePdf'])->name('financial-reports.popote-pdf');
    Route::get('financial-reports/charges-fixes', [FinancialReportController::class, 'chargesFixesReport'])->name('financial-reports.charges-fixes');
    Route::get('financial-reports/revenues-by-category', [FinancialReportController::class, 'revenuesByCategory'])->name('financial-reports.revenues-by-category');
    Route::post('financial-reports/revenues-by-category/store', [FinancialReportController::class, 'storeRevenuesByCategory'])->name('financial-reports.revenues-by-category.store');
    Route::get('financial-reports/revenues-by-category/pdf', [FinancialReportController::class, 'downloadRevenuesByCategoryPdf'])->name('financial-reports.revenues-by-category.pdf');

    // Routes avec paramètres (doivent être après les routes spécifiques)
    Route::get('financial-reports/{financialReport}/pdf', [FinancialReportController::class, 'downloadPdf'])->name('financial-reports.download-pdf');
    Route::get('financial-reports/{financialReport}', [FinancialReportController::class, 'show'])->name('financial-reports.show');
    Route::post('financial-reports', [FinancialReportController::class, 'store'])->name('financial-reports.store');

    // Groupes (permissions définies via spatie/permission)
    Route::resource('groups', GroupController::class);

    // Rôles & permissions (administration)
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('permissions', PermissionController::class)->except(['show']);
});
