<?php

declare(strict_types=1);

namespace Cortex\Settings\Http\Requests;

use Rinvex\Support\Traits\Escaper;
use Cortex\Foundation\Http\FormRequest;

class SettingFormRequest extends FormRequest
{
    use Escaper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $data = $this->all();

        if (in_array($data['type'], ['text', 'email', 'phone', 'password', 'date-picker', 'color-picker', 'icon-picker', 'style-picker', 'boolean'])) {
            unset($data['options']);
        }

        if (in_array($data['type'], ['checkbox', 'multi-select', 'resource']) && is_array($data['value'])) {
            $data['value'] = json_encode($data['value']);
        }

        if (! isset($data['override_config'])) {
            $data['override_config'] = false;
        }

        $this->replace($data);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $setting = $this->route('setting') ?? app('cortex.settings.setting');
        $setting->updateRulesUniques();

        return $setting->getRules();
    }
}
