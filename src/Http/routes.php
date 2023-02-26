<?php
// These routes are for example in local:

use Fomvasss\Lte3\Http\Controllers\ExampleController;

Route::view('/', 'lte3::examples.home');
Route::view('home2', 'lte3::examples.home2');
Route::view('home3', 'lte3::examples.home3');
Route::view('blank', 'lte3::examples.blank');
Route::view('forms', 'lte3::examples.forms');
Route::view('login', 'lte3::auth.login');
Route::view('register', 'lte3::auth.register');
Route::view('forgot-password', 'lte3::auth.forgot-password');
Route::view('reset-password', 'lte3::auth.reset-password');

Route::get('components', [ExampleController::class, 'components'])->name('components');
Route::any('data/save', 'ExampleController@save')->name('data.save');
Route::get('data/statuses', 'ExampleController@statuses')->name('data.statuses');
Route::get('data/modal-content', 'ExampleController@modalContent')->name('data.modal-content');
Route::post('data/statuses', 'ExampleController@status')->name('data.status');
Route::any('data/treeselect', 'ExampleController@treeselect')->name('data.treeselect');
Route::get('data/treeview', 'ExampleController@treeview')->name('data.treeview');
Route::get('data/tags', 'ExampleController@tags')->name('data.tags');

