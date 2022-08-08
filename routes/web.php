<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAjaxController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::resource('/tasks', TaskController::class);

// Route::get('/taskslive', function(){
//     return view('taskslive.index');
// });

Route::resource('tasks', TaskAjaxController::class);
Route::get('tasks/get/tasks', [TaskAjaxController::class, 'getTasks']);
Route::get('tasks/get/users', [TaskAjaxController::class, 'getUsers']);
Route::get('tasks/get/search', [TaskAjaxController::class, 'search']);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
