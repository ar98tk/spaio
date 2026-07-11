<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\RegisteredTenantController;
use App\Http\Controllers\Auth\VerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantWebsiteActionController;
use App\Http\Controllers\TenantWebsiteController;
use App\Livewire\Panel\Overview as PanelOverview;
use App\Livewire\Panel\Packages as PanelPackages;
use App\Livewire\Panel\Services as PanelServices;
use App\Livewire\Panel\Staff as PanelStaff;
use App\Livewire\Panel\Gallery as PanelGallery;
use App\Livewire\Panel\Templates as PanelTemplates;
use App\Livewire\Panel\Users as PanelUsers;
use App\Livewire\Panel\Reception as PanelReception;
use App\Livewire\Panel\Bookings as PanelBookings;
use App\Livewire\Panel\Statistics as PanelStatistics;
use App\Livewire\Panel\Settings as PanelSettings;
use App\Livewire\Panel\Billing as PanelBilling;
use Illuminate\Support\Facades\Route;

$tenantRootDomain = parse_url((string) config('app.url'), PHP_URL_HOST);

if ($tenantRootDomain && ! in_array($tenantRootDomain, ['localhost', '127.0.0.1'], true)) {
    Route::domain('{tenantSubdomain}.'.$tenantRootDomain)->group(function () {
        Route::get('/', [TenantWebsiteController::class, 'showBySubdomain'])->name('tenant.website');
        Route::post('/book', [BookingController::class, 'storeBySubdomain'])->name('tenant.website.booking');
        Route::post('/actions', [TenantWebsiteActionController::class, 'storeBySubdomain'])->name('tenant.website.actions');
    });
}

Route::get('/', [MarketingController::class, 'home'])->name('home');
Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
Route::get('/site/{tenant:subdomain}', [TenantWebsiteController::class, 'preview'])->name('tenant.preview');
Route::post('/site/{tenant:subdomain}/book', [BookingController::class, 'store'])->name('tenant.preview.booking');
Route::post('/site/{tenant:subdomain}/actions', [TenantWebsiteActionController::class, 'store'])->name('tenant.preview.actions');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredTenantController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredTenantController::class, 'store']);
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', VerificationPromptController::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

Route::middleware(['auth', 'verified', 'tenant-context'])->group(function () {
    Route::prefix('panel')->name('panel.')->group(function () {
        Route::get('/', PanelOverview::class)->name('index');
        Route::get('/services', PanelServices::class)
            ->middleware('tenant-permission:services.create,services.update,services.delete')
            ->name('services');
        Route::get('/packages', PanelPackages::class)
            ->middleware('tenant-permission:packages.create,packages.update,packages.delete')
            ->name('packages');
        Route::get('/staff', PanelStaff::class)
            ->middleware('tenant-permission:staff.create,staff.update,staff.delete')
            ->name('staff');
        Route::get('/gallery', PanelGallery::class)
            ->middleware('tenant-permission:gallery.create,gallery.update,gallery.delete')
            ->name('gallery');
        Route::get('/templates', PanelTemplates::class)
            ->middleware('tenant-permission:templates.change')
            ->name('templates');
        Route::get('/users', PanelUsers::class)
            ->middleware('tenant-permission:users.create,users.update,users.delete')
            ->name('users');
        Route::get('/reception', PanelReception::class)
            ->middleware('tenant-permission:reception.access,bookings.create')
            ->name('reception');
        Route::get('/bookings', PanelBookings::class)
            ->middleware('tenant-permission:bookings.create,bookings.update,bookings.delete')
            ->name('bookings');
        Route::get('/statistics', PanelStatistics::class)
            ->middleware('tenant-permission:analytics.view')
            ->name('statistics');
        Route::get('/settings', PanelSettings::class)
            ->middleware('tenant-permission:settings.update')
            ->name('settings');
        Route::get('/billing', PanelBilling::class)
            ->middleware('tenant-permission:billing.control')
            ->name('billing');
        Route::get('/billing/invoices/{subscription}', [BillingController::class, 'invoice'])
            ->middleware('tenant-permission:billing.control')
            ->name('billing.invoice');
    });
});

Route::middleware(['auth', 'super-admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/tenants', [SuperAdminController::class, 'storeTenant'])->name('tenants.store');
        Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants');
        Route::put('/tenants/{tenant}', [SuperAdminController::class, 'updateTenant'])->name('tenants.update');
        Route::delete('/tenants/{tenant}', [SuperAdminController::class, 'destroyTenant'])->name('tenants.destroy');
        Route::get('/tenants/{tenant}', [SuperAdminController::class, 'tenant'])->name('tenants.show');
        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::put('/users/{user}', [SuperAdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [SuperAdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/plans', [SuperAdminController::class, 'storePlan'])->name('plans.store');
        Route::get('/plans', [SuperAdminController::class, 'plans'])->name('plans');
        Route::put('/plans/{plan}', [SuperAdminController::class, 'updatePlan'])->name('plans.update');
        Route::delete('/plans/{plan}', [SuperAdminController::class, 'destroyPlan'])->name('plans.destroy');
        Route::post('/subscriptions', [SuperAdminController::class, 'storeSubscription'])->name('subscriptions.store');
        Route::get('/subscriptions', [SuperAdminController::class, 'subscriptions'])->name('subscriptions');
        Route::put('/subscriptions/{subscription}', [SuperAdminController::class, 'updateSubscription'])->name('subscriptions.update');
        Route::delete('/subscriptions/{subscription}', [SuperAdminController::class, 'destroySubscription'])->name('subscriptions.destroy');
        Route::post('/templates', [SuperAdminController::class, 'storeTemplate'])->name('templates.store');
        Route::get('/templates', [SuperAdminController::class, 'templates'])->name('templates');
        Route::put('/templates/{websiteTemplate}', [SuperAdminController::class, 'updateTemplate'])->name('templates.update');
        Route::delete('/templates/{websiteTemplate}', [SuperAdminController::class, 'destroyTemplate'])->name('templates.destroy');
        Route::get('/marketing-settings', [SuperAdminController::class, 'marketingSettings'])->name('marketing-settings');
        Route::put('/marketing-settings', [SuperAdminController::class, 'updateMarketingSettings'])->name('marketing-settings.update');
        Route::get('/platform-settings', [SuperAdminController::class, 'platformSettings'])->name('platform-settings');
    });
