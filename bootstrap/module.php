<?php

declare(strict_types=1);

return function () {
    // Bind route models and constrains
    Route::pattern('group', '[a-zA-Z0-9]+');
    Route::pattern('setting', '[a-zA-Z0-9.]+');
    Route::model('setting', config('rinvex.settings.models.setting'));
};
