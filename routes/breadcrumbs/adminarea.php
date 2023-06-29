<?php

declare(strict_types=1);

use Cortex\Settings\Models\Setting;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;

Breadcrumbs::for('adminarea.cortex.settings.settings.index', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.home');
    $breadcrumbs->push(trans('cortex/settings::common.settings'), route('adminarea.cortex.settings.settings.index'));
});

Breadcrumbs::for('adminarea.cortex.settings.settings.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.cortex.settings.settings.index');
    $breadcrumbs->push(trans('cortex/settings::common.create_new_settings'), route('adminarea.cortex.settings.settings.create'));
});

Breadcrumbs::for('adminarea.cortex.settings.settings.edit', function (Generator $breadcrumbs, Setting $setting) {
    $breadcrumbs->parent('adminarea.cortex.settings.settings.index');
    $breadcrumbs->push(strip_tags($setting->name), route('adminarea.cortex.settings.settings.edit', ['setting' => $setting]));
});
