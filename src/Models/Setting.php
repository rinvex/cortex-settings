<?php

declare(strict_types=1);

namespace Cortex\Settings\Models;

use Spatie\Activitylog\LogOptions;
use Rinvex\Support\Traits\Macroable;
use Rinvex\Support\Traits\HashidsTrait;
use Rinvex\Support\Traits\HasTimezones;
use Cortex\Foundation\Traits\Auditable;
use Cortex\Settings\Events\SettingCreated;
use Cortex\Settings\Events\SettingDeleted;
use Cortex\Settings\Events\SettingUpdated;
use Cortex\Settings\Events\SettingRestored;
use Spatie\Activitylog\Traits\LogsActivity;
use Rinvex\Settings\Models\Setting as BaseSetting;

class Setting extends BaseSetting
{
    use Macroable;
    use Auditable;
    use HashidsTrait;
    use HasTimezones;
    use LogsActivity;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => SettingCreated::class,
        'updated' => SettingUpdated::class,
        'deleted' => SettingDeleted::class,
        'restored' => SettingRestored::class,
    ];

    /**
     * Set sensible Activity Log Options.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logFillable()
                         ->logOnlyDirty()
                         ->dontSubmitEmptyLogs();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'key';
    }
}
