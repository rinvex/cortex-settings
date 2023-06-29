<?php

declare(strict_types=1);

namespace Cortex\Settings\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Cortex\Settings\Models\Setting;
use Cortex\Foundation\Http\FormRequest;
use Cortex\Settings\Exports\SettingsExport;
use Cortex\Foundation\Importers\UpsertImporter;
use Cortex\Settings\Exports\SettingsBackupExport;
use Cortex\Settings\Imports\SettingsRestoreImport;
use Illuminate\Database\Eloquent\Relations\Relation;
use Cortex\Settings\Http\Requests\SettingFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class SettingController extends AuthorizedController
{
    public function index(Request $request)
    {
        $settings = app('rinvex.settings.setting')->get()->groupBy('group');

        $SelectedGroupKey = $request->route('group_key');
        if (! empty($SelectedGroupKey)) {
            $selected_type = app('rinvex.settings.setting')->when($request->route('group_key') !== 'all', function ($query) use ($SelectedGroupKey) {
                $query->where('key', 'LIKE', $SelectedGroupKey."%");
            })->get()->groupBy('group');
        } else {
            $selected_type = $settings;
        }

        $map_group = $settings->map(function ($grp, $key) {
            return [
                'text' => $key,
                'children' => $grp->map(function ($stg) use ($key) {
                    return ['id' => $key, 'text' => $stg->key];
                }),
            ];
        })->values()->toArray();

        $groups = Arr::prepend($settings->keys()->map(function ($key) {
            return ['id' => $key, 'text' => $key];
        })->toArray(), ['id' => 'all', 'text' => trans('cortex/settings::common.show_all_settings')]);

        $expended_groups = Arr::prepend($map_group, ['id' => 'all', 'text' => trans('cortex/settings::common.show_all_settings')]);

        return view('cortex/settings::adminarea.settings.index')->with([
                'groups' => $groups,
                'expended_groups' => $expended_groups,
                'pusher' => ['entity' => 'setting', 'channel' => 'cortex.settings.settings.index'],
                'selected_type' => $selected_type,
            ]);
    }

    public function export()
    {
        return Excel::download(new SettingsExport, 'settings-'.now()->timestamp.'.xlsx');
    }

    public function downloadSettingsBackup()
    {
        return Excel::download(new SettingsBackupExport, 'settings-backup-'.now()->timestamp.'.xlsx');
    }

    public function restoreSettings(Request $request)
    {
        Excel::import(new SettingsRestoreImport, $request->file('file'));

        return intend([
            'url' => route('adminarea.cortex.settings.settings.index'),
            'with' => ['success' => trans('cortex/foundation::messages.import_complete')],
        ]);
    }

    public function import(Request $request, UpsertImporter $importer, Setting $setting)
    {
        $importer->withModel($setting)->withUniqueBy(['key'])->import($request->file('file'));

        return intend([
            'url' => route('adminarea.cortex.settings.settings.index'),
            'with' => ['success' => trans('cortex/foundation::messages.import_complete')],
        ]);
    }

    public function create(Request $request, Setting $setting)
    {
        return $this->form($request, $setting);
    }

    public function edit(Request $request, Setting $setting)
    {
        return $this->form($request, $setting);
    }

    /**
     * Show setting create/edit form.
     *
     * @param \Illuminate\Http\Request        $request
     * @param \Cortex\Settings\Models\Setting $setting
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function form(Request $request, Setting $setting)
    {
        if (! $setting->exists && $request->has('replicate') && $replicated = $setting->resolveRouteBinding($request->input('replicate'))) {
            $setting = $replicated->replicate();
        }

        $resources = collect(Relation::morphMap())->map(function ($value, $key) {
            return Str::headline($key);
        })->sort();

        return view('cortex/settings::adminarea.settings.create', compact('setting', 'resources'));
    }

    public function store(SettingFormRequest $request, Setting $setting)
    {
        return $this->process($request, $setting);
    }

    public function update(SettingFormRequest $request, Setting $setting)
    {
        return $this->process($request, $setting);
    }

    public function process(FormRequest $request, Setting $setting)
    {
        $data = $request->all();

        $setting->fill($data);

        $setting->save();

        return intend([
            'url' => route('adminarea.cortex.settings.settings.index'),
            'with' => ['success' => trans('New Setting Added Successfully')],
        ]);
    }

    public function modify(Request $request, Setting $setting)
    {
        if (in_array($request->get('key'), ['value', 'override_config'])) {
            $value = $request->get('value');

            if ($setting->type == 'checkbox' && is_array($value)) {
                $value = json_encode($value);
            }

            if ($request->get('key') === 'override_config') {
                $value = (int) $value;
            }

            $setting->update([$request->get('key') => $value]);

            return response()->json(['status' => 'success', 'message' => trans('Settings updated successfully')]);
        }

        return response('there is something wrong', 400);
    }

    public function batchUpdate(Request $request)
    {
        foreach ($request->get('setting', []) as $routeKey => $data) {
            $setting = (new Setting())->resolveRouteBinding($routeKey);
            if (is_null($setting)) {
                continue;
            }

            if (isset($data['value'])) {
                if (is_array($data['value'])) {
                    $setting->value = json_encode($data['value']);
                } else {
                    $setting->value = $data['value'];
                }
            }

            $setting->override_config = Arr::get($data, 'override_config', false);

            $setting->save();
        }

        return intend([
            'back' => true,
            'with' => ['success' => trans('Settings updated successfully')],
        ]);
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return intend([
            'url' => route('adminarea.cortex.settings.settings.index'),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => trans('cortex/settings::common.setting'), 'identifier' => $setting->getRouteKey()])],
        ]);
    }
}
