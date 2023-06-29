<?php

declare(strict_types=1);

use Cortex\Settings\Http\Controllers\SettingController;

Route::domain('{adminarea}')->group(function () {
    Route::name('adminarea.')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(route_prefix('adminarea'))->group(function () {

            Route::name('cortex.settings.settings.')->prefix('settings')->group( function () {
                Route::match(['get', 'post'], '/{group_key?}')->name('index')->uses([SettingController::class, 'index']);
                Route::get('export')->name('export')->uses([SettingController::class, 'export']);
                Route::get('backup')->name('backup')->uses([SettingController::class, 'downloadSettingsBackup']);
                Route::post('import')->name('import')->uses([SettingController::class, 'import']);
                Route::post('restore')->name('restore')->uses([SettingController::class, 'restoreSettings']);
                Route::get('create')->name('create')->uses([SettingController::class, 'create']);
                Route::post('create')->name('store')->uses([SettingController::class, 'store']);
                Route::get('{setting}/edit')->name('edit')->uses([SettingController::class, 'edit']);
                Route::put('{setting}/edit')->name('update')->uses([SettingController::class, 'update']);
                Route::post('{setting}/modify')->name('modify')->uses([SettingController::class, 'modify']);
                Route::post('batch-update')->name('batch-update')->uses([SettingController::class, 'batchUpdate']);
                Route::delete('{setting}')->name('destroy')->uses([SettingController::class, 'destroy']);
            });
         });
});
