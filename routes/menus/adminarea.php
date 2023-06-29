<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Cortex\Settings\Models\Setting;
use Rinvex\Menus\Models\MenuGenerator;

Menu::register('adminarea.header.system', function (MenuGenerator $menu) {
    $menu->dropdown(function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.cortex.settings.settings.index'], trans('cortex/settings::common.settings'), null, 'fa fa-cog');
    }, trans('cortex/settings::common.system'), 10, 'fa fa-cogs');
});

Menu::register('adminarea.cortex.settings.settings.tabs', function (MenuGenerator $menu, Setting $setting) {
    $menu->route(['adminarea.cortex.settings.settings.create'], trans('cortex/auth::common.details'))->ifCan('create', $setting)->if(Route::is('adminarea.cortex.settings.settings.create'));
    $menu->route(['adminarea.cortex.settings.settings.edit', ['setting' => $setting]], trans('cortex/auth::common.details'))->ifCan('update', $setting)->if($setting->exists);
});
