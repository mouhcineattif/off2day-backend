<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Companies\Controllers\CompanyController;
use App\Modules\Companies\Controllers\WorkspaceController;
use App\Modules\Companies\Controllers\InviteController;

// Everything here is automatically loaded inside routes/api.php context
// via CompaniesServiceProvider. Do NOT add 'api' prefix here.

// Public route (workspace creation)
Route::post('workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');

// Protected company routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('companies', CompanyController::class);

    // Workspace-level management
    Route::prefix('workspaces/{company}')->group(function () {
        Route::post('invites', [InviteController::class, 'store'])->name('workspaces.invite');
    });

    // Accept invite (public-ish but controlled by token)
    Route::post('invites/accept', [InviteController::class, 'accept'])->name('invites.accept');
});
