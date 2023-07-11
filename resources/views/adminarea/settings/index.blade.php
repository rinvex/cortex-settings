{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('styles')
    <link href="{{ mix('css/settings.css') }}" rel="stylesheet">
@endpush
{{-- Main Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>
        @include('cortex/foundation::adminarea.partials.modal', ['id' => 'delete-confirmation'])

        {{-- Main content --}}
        <section class="content">

            @if(empty(request()->route('group_key')))
                <div class="panel panel-default">
                    <div class="panel-heading bg-dimgray">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="margin-bottom-none">{{trans('cortex/settings::common.cortex_settings')}}</h4>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('adminarea.cortex.settings.settings.create')}}" class="btn btn-primary pull-right" id="add-new-type-btn">{{trans('Create Setting')}}</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body" style="min-height: 400px">
                        <div class="row">
                            <div class="col-md-6" style="min-height: 400px">
                                <p class="h4">{{trans('cortex/settings::common.setting_to_edit')}}</p>
                                <p>{{ trans('cortex/settings::common.setting_to_edit_instruction') }}</p>
                                <a href="javascript:void(0)" id="setting-toggle">{{trans('cortex/settings::common.expand_settings')}}</a>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <a href="{{ route('adminarea.cortex.settings.settings.export') }}" target="_blank">{{trans('cortex/settings::common.export_settings')}}</a>
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="h4">/</span>
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::open(['url' => route('adminarea.cortex.settings.settings.import'), 'method' => 'post', 'id' => 'import-settings-form', 'files' => true]) }}
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <span class="btn btn-file no-padding">
                                                        <a href="javascript:void(0)">{{ trans('cortex/settings::common.import_settings') }}</a>
                                                        {{ Form::file('file', ['class' => 'form-control skip-validation', 'id' => 'import-settings-file']) }}
                                                    </span>
                                                </span>
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>

                                <br>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <a href="{{ route('adminarea.cortex.settings.settings.backup') }}" target="_blank">{{trans('cortex/settings::common.export_settings_backup')}}</a>

                                    </div>
                                    <div class="col-sm-1">
                                        <span class="h4">/</span>
                                    </div>
                                    <div class="col-sm-2">
                                        {{ Form::open(['url' => route('adminarea.cortex.settings.settings.restore'), 'method' => 'post', 'id' => 'restore-settings-form', 'files' => true]) }}
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <span class="btn btn-file no-padding">
                                                        <a href="javascript:void(0)">{{ trans('cortex/settings::common.restore_settings') }}</a>
                                                        {{ Form::file('file', ['class' => 'form-control skip-validation', 'id' => 'restore-settings-file']) }}
                                                    </span>
                                                </span>
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control select2 select-setting-type" id="setting-select2" name="group_key" size="20">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="go-setting-group" class="btn btn-primary btn-sm pull-right">{{ trans('cortex/settings::common.edit_settings') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="panel panel-default">
                    <div class="panel-heading bg-dimgray">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h4 class="margin-bottom-none">{{trans('cortex/settings::common.setting_group')}}</h4>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control select-setting-type select2" name="group_key">
                                            @foreach($groups as $group)
                                                <option value="{{$group['id']}}" {{request()->route('group_key') == $group['id']?'selected':''}}>{{$group['text']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button id="go-setting-group" type="submit" class="btn btn-default">Go</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('adminarea.cortex.settings.settings.create')}}" class="btn btn-primary pull-right" id="add-new-type-btn">{{trans('Create Setting')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @foreach($selected_type ?? [] as $key => $settings)
                            {{Form::open(['url' => route('adminarea.cortex.settings.settings.batch-update'), 'class' => 'bulk-settings-from', 'method' => 'post'])}}
                                <div class="panel panel-default">
                                    <div class="panel-heading bg-dimgray">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <span class="h5 text-bold">{{ $key }}</span>
                                            </div>
                                            <div class="col-sm-5">
                                            </div>
                                            <div class="col-sm-2">
                                                <span class="h5 text-bold">{{ trans('cortex/settings::common.config_override') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body no-padding">
                                        @foreach($settings as $setting)
                                            <div class="panel panel-default no-margin border-bottom-none">
                                                <div class="panel-heading no-padding ">
                                                    <div class="row padding-y">
                                                        <div class="col-sm-5">
                                                            <h5 class="text-bold">
                                                                {{ $setting->key }}
                                                            </h5>
                                                        </div>
                                                        <div class="col-sm-5">
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="btn-group-sm pull-right" style="padding-top: 6px">
                                                                <a href="{{ route('adminarea.cortex.settings.settings.edit', ['setting' => $setting]) }}">
                                                                    {{trans('cortex/settings::common.edit')}}
                                                                </a>
                                                                <a href="#"
                                                                   data-toggle="modal"
                                                                   data-target="#delete-confirmation"
                                                                   data-modal-action="{{ route('adminarea.cortex.settings.settings.destroy', ['setting' => $setting] ) }}"
                                                                   data-modal-title="{{ trans('cortex/foundation::messages.delete_confirmation_title') }}"
                                                                   data-modal-button="<a href='#' class='btn btn-danger' data-form='delete' data-token='{{ csrf_token() }}'><i class='fa fa-trash-o'></i> {{ trans('cortex/foundation::common.delete') }}</a>"
                                                                   data-modal-body="{{ trans('cortex/foundation::messages.delete_confirmation_body', ['resource' => trans('cortex/settings::common.setting'), 'identifier' => $setting->getRouteKey()]) }}"
                                                                   title="{{ trans('cortex/foundation::common.delete') }}">
                                                                    {{ trans('cortex/settings::common.delete') }}
                                                                </a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-sm-5">
                                                            <div class="margin">
                                                                {!! htmlspecialchars_decode($setting->description) !!}
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-5">
                                                            @if(View::exists("cortex/settings::adminarea.settings.partials.fields.{$setting->type}"))
                                                                @include("cortex/settings::adminarea.settings.partials.fields.{$setting->type}")
                                                            @endif
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div>
                                                                <label class="setting-update switch">
                                                                    <input type="checkbox"
                                                                           class="setting-fields"
                                                                           id="'override-config-{{$setting->getRouteKey()}}"
                                                                           data-id="{{$setting->getRouteKey()}}"
                                                                           name="setting[{{$setting->getRouteKey()}}][override_config]"
                                                                           {{!$setting->override_config?:'checked'}}
                                                                           value="1">
                                                                    <span class="slider"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="#" class="btn btn-default btn-sm pull-right">{{ trans('cortex/settings::common.reset') }}</a>
                                                <button class="btn btn-success btn-sm pull-right">{{ trans('cortex/settings::common.save') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            {{Form::close()}}
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection
@push('inline-scripts')
    <script src="{{ mix('js/settings.js') }}" defer></script>
    <script>
        const expended_groups = @json($expended_groups);
        const groups = @json($groups);
        var unsaved = false;
    </script>
@endpush
