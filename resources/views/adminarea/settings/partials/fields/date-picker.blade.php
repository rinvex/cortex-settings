<div class="form-group">
    <input type="text"
           class="form-control daterangepicker"
           name="setting[{{$setting->getRouteKey()}}][value]"
           data-id="{{$setting->getRouteKey()}}"
           data-type="{{$setting->group}}"
           value="{{$setting->value}}"
           data-locale='{"format": "YYYY-MM-DD"}'
           data-single-date-picker="true"
           data-show-dropdowns="true"
           data-auto-apply="true"
    >
</div>
