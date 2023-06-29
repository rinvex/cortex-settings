<div class="form-group">
    <input type="password"
           class="form-control setting-update"
           name="setting[{{$setting->getRouteKey()}}][value]"
           data-id="{{$setting->getRouteKey()}}"
           data-type="{{$setting->group}}"
           value="{{$setting->value}}"
    >
</div>
