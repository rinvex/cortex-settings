@php
    $value = json_decode($setting->value);
@endphp
<div class="form-group">
    <select class="form-control select2 setting-update"
            id="{{$setting->key}}"
            data-id="{{$setting->getRouteKey()}}"
            data-type="{{$setting->group}}"
            multiple="multiple"
            name="setting[{{$setting->getRouteKey()}}][value][]">
        <option disabled selected></option>
        @foreach($setting->options as $option)
            @if(is_array($option))
                <option value="{{\Arr::get($option, 'key', Arr::first($option))}}" {{$setting->value == \Arr::get($option, 'key', Arr::first($option))?'selected':''}}>{{\Arr::get($option, 'text', Arr::first($option))}}</option>
            @else
                <option value="{{$option}}" {{$setting->value == $option?'selected':''}}>{{$option}}</option>
            @endif

            <option value="{{ \Arr::get($option, 'text', Arr::first($option)) }}"
                @if(isset($value) && is_array($value))
                    {{in_array(\Arr::get($option, 'key', Arr::first($option)), $value)?'selected':''}}
                @else
                    {{ \Arr::get($option, 'key', Arr::first($option)) == $setting->value?'selected':''}}
                @endif
            >{{$option}}</option>
        @endforeach
    </select>
</div>
