@foreach($setting->options as $option)
    <label for="{{$setting->key}}" class=" margin">
        <input type="radio"
           id="{{$setting->key}}"
           class="setting-update"
           name="setting[{{$setting->getRouteKey()}}][value]"
           data-id="{{$setting->getRouteKey()}}"
           data-type="{{$setting->group}}"
           value="{{$option}}"
            @if(is_array($option))
                {{$setting->value == \Arr::get($option, 'key', Arr::first($option))?'selected':''}}
            @else
                {{$setting->value == $option?'selected':''}}
            @endif
        >
        {{ \Arr::get($option, 'text', Arr::first($option)) }}
    </label>
@endforeach
