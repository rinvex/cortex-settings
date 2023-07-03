<?php

declare(strict_types=1);

namespace Cortex\Settings\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

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
