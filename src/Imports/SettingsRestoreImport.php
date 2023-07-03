<?php

declare(strict_types=1);

namespace Cortex\Settings\Imports;

use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SettingsRestoreImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $setting = app('rinvex.settings.setting')->where('key', Arr::get($row, 'key'))->first();

        if (! $setting) {
            return null;
        }

        $setting->update(['value' => Arr::get($row, 'value')]);

        return $setting;
    }
}
