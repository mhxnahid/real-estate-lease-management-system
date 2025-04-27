<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect('/admin/home'); });

// Authentication Routes...
Route::get('login', '\App\Http\Controllers\Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('login', '\App\Http\Controllers\Auth\LoginController@login')->name('auth.login');
Route::post('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
Route::get('change_password', '\App\Http\Controllers\Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', '\App\Http\Controllers\Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
Route::get('password/reset', '\App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
Route::post('password/email', '\App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
Route::get('password/reset/{token}', '\App\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', '\App\Http\Controllers\Auth\ResetPasswordController@reset')->name('auth.password.reset');

// Registration Routes..
Route::get('register', '\App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('auth.register');
Route::post('register', '\App\Http\Controllers\Auth\RegisterController@register')->name('auth.register');

Route::get('invitation/{invitation_token}/{user}/{lt}', '\App\Http\Controllers\Auth\RegisterController@processInvitation')->name('auth.invitation');

Route::group(['middleware' => ['auth', 'check_invitation'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', '\App\Http\Controllers\HomeController@index');
    
    Route::resource('permissions', '\App\Http\Controllers\Admin\PermissionsController');
    Route::post('permissions_mass_destroy', ['uses' => '\App\Http\Controllers\Admin\PermissionsController@massDestroy', 'as' => 'permissions.mass_destroy']);
    Route::resource('roles', '\App\Http\Controllers\Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => '\App\Http\Controllers\Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', '\App\Http\Controllers\Admin\UsersController');
    Route::post('users_mass_destroy', ['uses' => '\App\Http\Controllers\Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::resource('properties', '\App\Http\Controllers\Admin\PropertiesController');
    Route::post('properties_mass_destroy', ['uses' => '\App\Http\Controllers\Admin\PropertiesController@massDestroy', 'as' => 'properties.mass_destroy']);
    Route::post('properties_restore/{id}', ['uses' => '\App\Http\Controllers\Admin\PropertiesController@restore', 'as' => 'properties.restore']);
    Route::delete('properties_perma_del/{id}', ['uses' => '\App\Http\Controllers\Admin\PropertiesController@perma_del', 'as' => 'properties.perma_del']);
    Route::resource('documents', '\App\Http\Controllers\Admin\DocumentsController');
    Route::post('documents_mass_destroy', ['uses' => '\App\Http\Controllers\Admin\DocumentsController@massDestroy', 'as' => 'documents.mass_destroy']);
    Route::post('documents_restore/{id}', ['uses' => '\App\Http\Controllers\Admin\DocumentsController@restore', 'as' => 'documents.restore']);
    Route::delete('documents_perma_del/{id}', ['uses' => '\App\Http\Controllers\Admin\DocumentsController@perma_del', 'as' => 'documents.perma_del']);
    Route::resource('notes', '\App\Http\Controllers\Admin\NotesController');
    Route::post('notes_mass_destroy', ['uses' => '\App\Http\Controllers\Admin\NotesController@massDestroy', 'as' => 'notes.mass_destroy']);
    Route::post('notes_restore/{id}', ['uses' => '\App\Http\Controllers\Admin\NotesController@restore', 'as' => 'notes.restore']);
    Route::delete('notes_perma_del/{id}', ['uses' => '\App\Http\Controllers\Admin\NotesController@perma_del', 'as' => 'notes.perma_del']);

    Route::model('messenger', '\App\Models\MessengerTopic');
    Route::get('messenger/inbox', '\App\Http\Controllers\Admin\MessengerController@inbox')->name('messenger.inbox');
    Route::get('messenger/outbox', '\App\Http\Controllers\Admin\MessengerController@outbox')->name('messenger.outbox');
    Route::resource('messenger', '\App\Http\Controllers\Admin\MessengerController');

    Route::resource('tenants', '\App\Http\Controllers\Admin\TenantsController');
    Route::resource('landlords', '\App\Http\Controllers\Admin\LandlordController');

    Route::resource('leases', '\App\Http\Controllers\Admin\LeaseController');
    Route::post('leases/accept_lease/{lease}', '\App\Http\Controllers\Admin\LeaseController@acceptLease')->name('leases.accept_lease');
 
});
