<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\CategoryController;
Route::prefix('admin')->name('admin.')->group(function() {
Route::resource('authors', AuthorController::class);  // Tạo các route cho CRUD
 Route::resource('publishers', PublisherController::class);  // Các route CRUD cho publishers
  Route::resource('categories', CategoryController::class);  // Các route CRUD cho categories
});
