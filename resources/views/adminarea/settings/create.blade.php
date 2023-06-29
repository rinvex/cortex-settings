{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection
@push('inline-scripts')
    {!! JsValidator::formRequest(\Cortex\Settings\Http\Requests\SettingFormRequest::class)->selector("#adminarea-cortex-settings-create-form")->ignore('.skip-validation') !!}
@endpush
@push('styles')
    <link href="{{ mix('css/settings.css') }}" rel="stylesheet">
@endpush
{{-- Main Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">
            <div class="nav-tabs-custom">
                {!! Menu::render('adminarea.cortex.settings.settings.tabs', 'nav-tab') !!}

                <div class="tab-content">

                    <div class="tab-pane active" id="details-tab">
                        @if ($setting->exists)
                            {{ Form::model($setting, ['url' => route('adminarea.cortex.settings.settings.update', ['setting' => $setting]), 'method' => 'put', 'id' => "adminarea-cortex-settings-create-form", 'files' => true]) }}
                        @else
                            {{ Form::model($setting, ['url' => route('adminarea.cortex.settings.settings.store'), 'id' => 'adminarea-cortex-settings-create-form', 'files' => true]) }}
                        @endif
                            <div class="settings-section">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{-- Key --}}
                                        <div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
                                            {{ Form::label('key', trans('cortex/settings::common.key'), ['class' => 'control-label']) }}
                                            {{ Form::text('key', null, ['class' => 'form-control setting-key', 'placeholder' => trans('cortex/settings::common.key'), 'required' => 'required', 'autofocus' => 'autofocus']) }}
                                            @if ($errors->has('key'))
                                                <span class="help-block">{{ $errors->first('key') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        {{-- value --}}
                                        <div class="form-group{{ $errors->has('value') ? ' has-error' : '' }}">
                                            {{ Form::label('value', trans('cortex/settings::common.value'), ['class' => 'control-label']) }}
                                            {{ Form::text('value', is_array($setting->value)?implode(',', $setting->value):'', ['class' => 'form-control', 'placeholder' => trans('cortex/settings::common.value')]) }}
                                            @if ($errors->has('value'))
                                                <span class="help-block">{{ $errors->first('value') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('override_config') ? ' has-error' : '' }}">
                                            <label>{{trans('cortex/settings::common.override_default_configuration')}}</label><br>
                                            <div>
{{--                                                {{dd($setting->override_config)}}--}}
                                                <label class="switch">
                                                    <input type="checkbox" name="override_config" {{$setting->override_config?'checked':''}} value="1">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            @if ($errors->has('override_config'))
                                                <span class="help-block">{{ $errors->first('override_config') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-12">
                                        {{-- description --}}
                                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                            {{ Form::label('description', trans('cortex/settings::common.description'), ['class' => 'control-label']) }}
                                            {{ Form::textarea('description', null, ['class' => 'form-control tinymce', 'rows' => '3', 'placeholder' => trans('cortex/settings::common.description')]) }}
                                            @if ($errors->has('description'))
                                                <span class="help-block">{{ $errors->first('description') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{trans('cortex/settings::common.content_type')}}</label>
                                            <select name="type" class="form-control select2" id="content-type-el">
                                                @foreach(config('cortex.settings.types') as $content_type)
                                                    <option value="{{$content_type}}"
                                                    @if($setting->exists)
                                                        {{ $setting->type == $content_type? 'selected': '' }}
                                                            @else
                                                        {{ $loop->iteration != 1?:'selected' }}
                                                            @endif
                                                    >{{Str::headline(Str::replace('-', ' ', $content_type))}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 {{ $setting->type !== config('cortex.settings.types.resource')?"hidden":'' }}" id="resource-select">
                                        {{-- resource --}}
                                        <div class="form-group{{ $errors->has('options') ? ' has-error' : '' }}">
                                            {{ Form::label('options', trans('cortex/settings::common.resource'), ['class' => 'control-label']) }}
                                            {{ Form::select('options[]', $resources, null, ['class' => 'form-control select2', 'placeholder' => trans('cortex/settings::common.resource'), 'data-width' => '100%']) }}
                                            @if ($errors->has('options'))
                                                <span class="help-block">{{ $errors->first('options') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($setting->type !== config('cortex.settings.types.resource'))
                                    <div class="option-section {{ empty($setting->options) && $setting->type !== config('cortex.settings.types.resource') ? 'hidden' : '' }}">
                                        <div class="add-options margin-bottom">
                                            @foreach($setting->options ?? [] as $option)
                                                <div class="row content-option margin-bottom">
                                                    <div class="col-md-6">
                                                        <div class="input-group input-group-multi">
                                                            <div class="input-group-addon">{{ trans('cortex/settings::common.option') }} {{ $loop->iteration }}</div>
                                                            <div class="col-xs-6">
                                                                <input
                                                                    value="{{ \Arr::get($option, 'key', Arr::first($option)) }}"
                                                                    type="text"
                                                                    name="options[{{$loop->iteration}}][key]"
                                                                    placeholder="{{ trans('cortex/settings::common.key') }}"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-xs-6 no-gutters">
                                                                <input
                                                                    value="{{ \Arr::get($option, 'text', Arr::first($option)) }}"
                                                                    type="text"
                                                                    name="options[{{$loop->iteration}}][text]"
                                                                    placeholder="{{ trans('cortex/settings::common.option_text') }}"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="input-group-addon btn btn-danger option-delete-btn">
                                                                <span class="fa fa-trash"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" id="add-option-btn" class="btn btn-success">{{trans('cortex/settings::common.add_option')}}</button>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    <div class="option-section hidden">
                                        <div class="add-options margin-bottom">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" id="add-option-btn" class="btn btn-success">{{trans('cortex/settings::common.add_option')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn pull-right btn-primary">{{trans('cortex/settings::common.submit')}}</button>
                                    @include('cortex/foundation::adminarea.partials.timestamps', ['model' => $setting])
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('inline-scripts')
    <script src="{{ mix('js/settings.js') }}" defer></script>
@endpush
