<?php

namespace Cortex\Settings\Exports;

use Cortex\Settings\Models\Setting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SettingsBackupExport implements FromCollection, WithHeadings
{
    protected $fields = [
        'key',
        'value',
    ];

    public function collection()
    {
        return app('rinvex.settings.setting')->get(['key', 'value'])->makeHidden(['group']);
    }

    public function headings(): array
    {
        return $this->fields;
    }
}
