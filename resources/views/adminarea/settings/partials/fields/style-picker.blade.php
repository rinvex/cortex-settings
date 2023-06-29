<div class="form-group">
    <input type="text"
           class="form-control setting-update style-picker"
           name="setting[{{$setting->getRouteKey()}}][value]"
           data-id="{{$setting->getRouteKey()}}"
           data-type="{{$setting->group}}"
           value="{{$setting->value}}"
           placeholder="{{ trans('cortex/settings::common.style') }}"
           data-placement="bottomRight"
    >
</div>
