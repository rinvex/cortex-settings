<?php

namespace Cortex\Settings\Http\Components\Adminarea;

use Illuminate\Support\Arr;
use Cortex\Settings\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Cortex\Foundation\Overrides\Livewire\Component;
use Illuminate\Database\Eloquent\Relations\Relation;

class SettingResourceDropdown extends Component
{
    public $resource;

    /**
     * @var Setting
     */
    public $setting;

    /**
     * @var Model
     */
    public $model;

    public function mount($setting)
    {
        $this->setting = $setting;
        $this->resource = Arr::first($setting->options);
        $this->model = app(Relation::getMorphedModel($this->resource));
    }

    public function render()
    {
        if ($field = $this->getField()) {
            $data = $this->model->get()->pluck($field, 'id');
            $selectedValue = json_decode($this->setting->value);
        }
        else {
            $data = null;
            $selectedValue = $this->setting->value;
        }

        return view('cortex/settings::adminarea.components.setting-resource-dropdown', compact('data', 'selectedValue'));
    }

    public function getField()
    {
        $genericFields = ['username', 'name', 'title', 'slug'];
        if ($this->resource) {
            $modelFields = $this->model->getFillable();
            foreach ($genericFields as $field) {
                if (in_array($field, $modelFields)) {
                    return $field;
                }
            }
        }
        return null;
    }
}
