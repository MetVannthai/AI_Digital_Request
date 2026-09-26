<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\RequestController as AdminRequestController;
use App\Http\Controllers\Admin\ToolsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/request/create', [PublicRequestController::class, 'create'])->name('request.create');
Route::post('/request', [PublicRequestController::class, 'store'])->name('request.store');
Route::get('/request/status', [PublicRequestController::class, 'status'])->name('request.status');
Route::post('/request/status', [PublicRequestController::class, 'showStatus'])->name('request.status.check');

Route::middleware(['auth', 'force.pin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('staff')->name('staff.')->middleware('role:staff')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:super_admin|admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->except('show')->middleware('permission:manage-items');
        Route::resource('items', ItemController::class)->except('show')->middleware('permission:manage-items');
        Route::post('/items/{item}/add-stock', [ItemController::class, 'addStock'])->middleware('permission:manage-stock')->name('items.add-stock');
        Route::post('/items/import', [ItemController::class, 'import'])->middleware('permission:manage-items')->name('items.import');
        Route::get('/requests/my', fn () => redirect()->route('admin.requests.index'))->name('requests.mine');
        Route::get('/requests', [AdminRequestController::class, 'index'])->middleware('permission:approve-requests')->name('requests.index');
        Route::get('/requests/{request}', [AdminRequestController::class, 'show'])->middleware('permission:approve-requests')->name('requests.show');
        Route::post('/requests/{request}/approve', [AdminRequestController::class, 'approve'])->middleware('permission:approve-requests')->name('requests.approve');
        Route::post('/requests/{request}/reject', [AdminRequestController::class, 'reject'])->middleware('permission:approve-requests')->name('requests.reject');
        Route::post('/requests/{request}/issue', [AdminRequestController::class, 'issue'])->middleware('permission:issue-stock')->name('requests.issue');
        Route::get('/transactions', [TransactionController::class, 'index'])->middleware('permission:view-reports')->name('transactions.index');
        Route::get('/stock/in', fn () => redirect()->route('admin.items.index'))->middleware('permission:manage-stock')->name('stock.in');
        Route::get('/stock/out', fn () => redirect()->route('admin.transactions.index', ['type' => 'OUT']))->middleware('permission:view-reports')->name('stock.out');
        Route::get('/reports/requests', fn () => redirect()->route('admin.requests.index'))->middleware('permission:view-reports')->name('reports.requests');
        Route::get('/reports/stock', fn () => redirect()->route('admin.transactions.index'))->middleware('permission:view-reports')->name('reports.stock');
        Route::get('/qrcode', [ToolsController::class, 'qrcode'])->middleware('permission:view-reports')->name('qrcode');
        Route::get('/stock/export', [ToolsController::class, 'exportItems'])->middleware('permission:export-data')->name('stock.export');
        Route::get('/transactions/export', [ToolsController::class, 'exportTransactions'])->middleware('permission:export-data')->name('transactions.export');

        Route::middleware('role:super_admin')->group(function () {
            Route::resource('departments', DepartmentController::class)->except('show');
            Route::resource('users', UserController::class)->except('show');
            Route::post('/users/{user}/reset-pin', [UserController::class, 'resetPin'])->name('users.reset-pin');
            Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
            Route::view('/roles', 'admin.placeholder', ['title' => 'Roles'])->name('roles.index');
        });
    });
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->hasRole('staff')) {
        return redirect()->route('staff.dashboard');
    }

    return redirect()->route('admin.dashboard');
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
