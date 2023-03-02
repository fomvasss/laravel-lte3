<?php

use Fomvasss\Lte3\Http\Controllers\ExampleController;

Route::view('/', 'lte3::examples.home')->name('home');
Route::view('home2', 'lte3::examples.home2');
Route::view('home3', 'lte3::examples.home3');
Route::view('blank', 'lte3::examples.blank');
Route::view('forms', 'lte3::examples.forms');
Route::view('login', 'lte3::auth.login');
Route::view('register', 'lte3::auth.register');
Route::view('forgot-password', 'lte3::auth.forgot-password');
Route::view('reset-password', 'lte3::auth.reset-password');
Route::get('components', [ExampleController::class, 'components'])->name('components');
Route::view('edit', 'lte3::examples.edit');


Route::any('data/save', [ExampleController::class, 'save'])->name('data.save');
Route::get('data/statuses', [ExampleController::class, 'statuses'])->name('data.statuses');
Route::get('data/modal-content', [ExampleController::class, 'modalContent'])->name('data.modal-content');
Route::any('data/treeselect', [ExampleController::class, 'treeselect'])->name('data.treeselect');
Route::get('data/treeview', [ExampleController::class, 'treeview'])->name('data.treeview');
Route::get('data/tags', [ExampleController::class, 'tags'])->name('data.tags');

