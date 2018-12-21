@extends('layouts.admin')

@section('content')

{!! Breadcrumbs::render('admin/settings') !!}

<h2>
    <span>{!! Html::pageIcon('fal fa-cogs') !!} Settings</span>
</h2>

<div class="content card">
    <div class="card-body">

        <form action="{{ url('admin/settings') }}" method="post" class="validate tabs labels-right" id="edit_settings_form">
            {!! Html::hiddenInput(['method' => 'post']) !!}

            <ul class="nav nav-tabs hash-tabs page-tabs" role="tablist">
                @php $i = 0 @endphp
                @foreach ( $tabs as $tab )
                <li class="nav-item">
                    <a class="nav-link {{ $i == 0 ? 'active' : '' }}" data-toggle="tab" href="#{{ str_slug($tab) }}" role="tab">{{ $tab }}</a>
                </li>
                @php $i++ @endphp
                @endforeach
            </ul>

            <div class="tab-content page-tabs-content">
                @php $i = 0 @endphp
                @foreach ( $tabs as $tab )
                <div class="tab-pane fade show {{ $i == 0 ? 'active' : '' }}" id="{{ str_slug($tab) }}" role="tabpanel">

                    @foreach ( $settings[$tab] as $setting )
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">{{ $setting->label }}</label>
                            <div class="col-sm-10">
                                @if ( $setting->type == 'select' )
                                    <select name="settings[{{ $setting->key }}]" class="form-control" {!! $setting->is_required ? 'data-fv-notempty="true"' : '' !!}>
                                        <option value="">- {{ $setting->label }} -</option>
                                        @foreach ( $setting->options as $option )
                                        <option value="{{ $option }}" {{ $setting->value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif ( $setting->type == 'checkbox' )
                                    <input type="hidden" name="settings[checkbox][]" value="{{ $setting->key }}">
                                    <div class="mt-2">
                                        @foreach ( $setting->options as $option )
                                        <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                                            <input type="checkbox" name="settings[{{ $setting->key }}][]" id="{{ $setting->key . '_' . $option }}" value="{{ $option }}" {{ in_array($option, $setting->value) ? 'checked' : '' }}>
                                            <label class="form-check-label mr-2" for="{{ $setting->key . '_' . $option }}">{{ $option }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                @elseif ( $setting->type == 'textarea' )
                                    <textarea name="settings[{{ $setting->key }}]" class="form-control" placeholder="{{ $setting->label }}" {!! $setting->is_required ? 'data-fv-notempty="true"' : '' !!}>{{ $setting->value }}</textarea>
                                @else
                                    <input type="text" name="settings[{{ $setting->key }}]" class="form-control" placeholder="{{ $setting->label }}" value="{{ $setting->value }}" {!! $setting->is_required ? 'data-fv-notempty="true"' : '' !!}>
                                @endif
                                <div class="form-text text-muted font-13">{{ $setting->description }}</div>
                            </div>
                        </div>
                    @endforeach

                </div>
                @php $i++ @endphp
                @endforeach
            </div>

            @if ( has_permission('admin.settings.update') )
            <div class="form-group row mt-5">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                </div>
            </div>
            @endif

        </form>

    </div>
</div>

@endsection