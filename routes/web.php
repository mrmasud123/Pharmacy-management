<?php

use App\Http\Controllers\Admin\PermisssionController;
use App\Http\Controllers\Admin\RolesController;
use Illuminate\Support\Facades\Route;
 
 

// dashboard pages
Route::get('/', function () {
    return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
})->name('dashboard');


Route::get('/roles', [RolesController::class, 'index'])->name('admin.roles');
Route::get('/roles/create', [RolesController::class, 'create'])->name('admin.roles.create');
Route::post('/roles', [RolesController::class, 'store'])->name('admin.roles.store');
Route::get('/roles/{role}/permissions', [RolesController::class, 'show'])->name('admin.add.permissions.to.role');
Route::put('/roles/{role}/permissions', [RolesController::class, 'assignPermission'])->name('admin.roles.update-permissions');
Route::get('/admin/roles/data', [RolesController::class, 'data'])->name('roles.data');


Route::get('/permissions', [PermisssionController::class, 'index'])->name('admin.permissions');
Route::get('/permissions/create', [PermisssionController::class, 'create'])->name('admin.permissions.create');
Route::post('/permissions', [PermisssionController::class, 'store'])->name('admin.permissions.store');



// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');

// profile pages
Route::get('/profile', function () {
    return view('pages.profile', ['title' => 'Profile']);
})->name('profile');

// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');






















