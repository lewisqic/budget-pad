@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')

@if ( $title == 'Edit' )
    {!! Breadcrumbs::render('admin/member-roles/edit', $role) !!}
@else
    {!! Breadcrumbs::render('admin/member-roles/create') !!}
@endif

<h2>
    <span>{!! Html::pageIcon('fal fa-key') !!} {{ $title }} Member Role <small>{{ $role->name ?? '' }}</small></span>
</h2>

<div class="content card">
    <div class="card-body">

        <form action="{{ url('admin/member-roles' . (isset($role) ? '/' . $role->id : '')) }}" method="post" class="validate labels-right" id="create_edit_role_form">
            <input type="hidden" name="id" value="{{ $role->id ?? '' }}">
            {!! Html::hiddenInput(['method' => isset($role) ? 'put' : 'post']) !!}

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Name</label>
                <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $role->name ?? old('name') }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Default</label>
                <div class="col-sm-9">
                    <select name="is_default" class="form-control">
                        <option value="0" {{ isset($role) && !$role->is_default ? 'selected' : '' }}>No</option>
                        <option value="1" {{ isset($role) && $role->is_default ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">
                    <sup><a href="#" data-toggle="tooltip" title="Select the permissions to be granted to users who are assigned to this role."><i class="fal fa-info-circle"></i></a></sup>
                    Permissions
                </label>
                <div class="col-sm-9 form-control-static">
                    <div class="permissions-wrapper">
                        @foreach ( $all_permissions as $group => $actions )
                        <div class="permission-group-wrapper mb-3">
                            <div class="group">
                                <div class="abc-checkbox abc-checkbox-primary">
                                    <input type="checkbox" class="permission-group" id="{{ $group }}">
                                    <label class="form-check-label" for="{{ $group }}">{{ $group }}</label>
                                </div>
                            </div>
                            <div class="functions ml-4">
                                @foreach ( $actions as $label => $id )
                                @php $id_arr = explode('|', $id) @endphp
                                <div class="abc-checkbox abc-checkbox-primary">
                                    <input type="checkbox" class="permission-function" name="permissions[]" id="{{ $id }}" value="{{ $id }}" {{ isset($role_permissions) && count(array_intersect($id_arr, $role_permissions)) == count($id_arr) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $id }}"><small>{{ $label }}</small></label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                    <a href="#" class="btn btn-secondary close-sidebar">Cancel</a>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ url('assets/js/modules/permissions.js') }}"></script>
@endpush