<input type="hidden" name="setting[{{$setting->getRouteKey()}}][value]" value="0" />
<label class="switch">
    <input type="checkbox"
           class="setting-update"
           id="{{$setting->key}}"
           data-id="{{$setting->getRouteKey()}}"
           name="setting[{{$setting->getRouteKey()}}][value]"
           data-type="{{$setting->group}}"
           {{ $setting->value?'checked':''}}
           value="1">
    <span class="slider"></span>
</label>
