@foreach($setting->options as $key => $option)
    @php
        $value = json_decode($setting->value);
    @endphp
    <label for="{{$setting->key}}" class=" margin">
        <input type="checkbox"
               id="{{$setting->key}}"
               class="setting-update"
               data-ref="value-{{$setting->key}}"
               name="setting[{{$setting->getRouteKey()}}][value][]"
               data-id="{{$setting->getRouteKey()}}"
               data-type="{{$setting->group}}"
               value="{{$option}}"
        @if(isset($value) && is_array($value))
            {{in_array(\Arr::get($option, 'key', Arr::first($option)), $value) ? 'checked' : ''}}
        @else
            {{ $setting->value == \Arr::get($option, 'key', Arr::first($option)) ? 'checked' : ''}}
        @endif
        >
        {{\Arr::get($option, 'text', Arr::first($option))}}
    </label>
@endforeach
