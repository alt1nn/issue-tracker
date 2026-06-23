<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::resource('projects', ProjectController::class);
Route::resource('projects.issues', IssueController::class)->shallow();

// AJAX routes për tags
Route::post('issues/{issue}/tags/{tag}/attach', [IssueController::class, 'attachTag'])->name('issues.tags.attach');
Route::delete('issues/{issue}/tags/{tag}/detach', [IssueController::class, 'detachTag'])->name('issues.tags.detach');

// AJAX routes për comments
Route::get('issues/{issue}/comments', [CommentController::class, 'index'])->name('issues.comments.index');
Route::post('issues/{issue}/comments', [CommentController::class, 'store'])->name('issues.comments.store');

// Tags
Route::resource('tags', TagController::class)->only(['index', 'store']);

require __DIR__ . '/settings.php';
