<div>
    @if(is_null($data))
        <div class="form-group">
            <input type="text"
                   class="form-control setting-update"
                   name="setting[{{$setting->getRouteKey()}}][value]"
                   data-id="{{$setting->getRouteKey()}}"
                   data-type="{{$setting->group}}"
                   value="{{$setting->value}}"
            >
        </div>
    @else
        <div class="form-group">
            <select class="form-control select2 setting-update"
                    id="{{$setting->key}}"
                    data-id="{{$setting->getRouteKey()}}"
                    data-type="{{$setting->group}}"
                    multiple="multiple"
                    name="setting[{{$setting->getRouteKey()}}][value][]">
                @foreach($data as $key => $text)

                    <option value="{{ $key }}"
                        @if(isset($selectedValue) && is_array($selectedValue))
                            {{in_array($key, $selectedValue)?'selected':''}}
                        @else
                            {{ $key == $selectedValue?'selected':''}}
                        @endif
                    >{{$text}}</option>
                @endforeach
            </select>
        </div>
    @endif
</div>
