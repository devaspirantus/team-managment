<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\MyTasks;
use App\Livewire\TaskManager;
use App\Http\Controllers\TaskExcelController;


Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // لوحة التحكم الافتراضية بعد تسجيل الدخول
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // صفحة إدارة المهام الخاصة بـ مدير النظام (Admin)
    Route::get('/admin/tasks', TaskManager::class)
        ->middleware(['admin']) // حماية الصفحة باستخدام Middleware للتحقق من صلاحية المدير
        ->name('admin.tasks');

    // صفحة المهام الخاصة بـ عضو الفريق (Member)
    Route::get('/my-tasks', MyTasks::class)
        ->name('my.tasks');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/tasks/export', [TaskExcelController::class, 'export'])->name('admin.tasks.export');
    Route::post('/admin/tasks/import', [TaskExcelController::class, 'import'])->name('admin.tasks.import');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
