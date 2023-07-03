<?php

declare(strict_types=1);

namespace Cortex\Settings\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SettingsExport implements FromCollection, WithHeadings
{
    protected $fields = [
        'key',
        'value',
        'type',
        'options',
        'description',
        'override_config',
    ];

    public function collection()
    {
        return app('rinvex.settings.setting')
            ->get($this->fields)
            ->map(function ($st) {
                $st->options = ! empty($st->options) ? json_encode($st->options) : null;

                return $st;
            })
            ->makeHidden(['group']);
    }

    public function headings(): array
    {
        return $this->fields;
    }
}
