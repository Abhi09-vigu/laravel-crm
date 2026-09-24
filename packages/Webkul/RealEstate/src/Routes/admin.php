<?php

use Illuminate\Support\Facades\Route;
use Webkul\RealEstate\Http\Controllers\ProjectController;

Route::group([
    'middleware' => ['web', 'admin_locale', 'user'],
    'prefix' => config('app.admin_path', 'admin'),
], function () {
    Route::controller(ProjectController::class)->prefix('real-estate/projects')->group(function () {
        Route::get('', 'index')->name('admin.real_estate.projects.index');

        Route::get('create', 'create')->name('admin.real_estate.projects.create');

        Route::post('create', 'store')->name('admin.real_estate.projects.store');

        Route::get('view/{id}', 'view')->name('admin.real_estate.projects.view');

        Route::get('edit/{id}', 'edit')->name('admin.real_estate.projects.edit');

        Route::put('edit/{id}', 'update')->name('admin.real_estate.projects.update');

        Route::delete('delete/{id}', 'destroy')->name('admin.real_estate.projects.delete');

        Route::put('{id}/status', 'updateStatus')->name('admin.real_estate.projects.update_status');

        Route::get('{id}/properties', 'properties')->name('admin.real_estate.projects.properties');
    });
});
