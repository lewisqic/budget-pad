@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/member-roles') !!}

    <div class="float-right">
        @if ( has_permission('admin.member-roles.create') )
        <a href="{{ url('admin/member-roles/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Role</a>
        @endif
    </div>
    <h2>
        <span>{!! Html::pageIcon('fal fa-key') !!} Member Roles</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                    <input type="checkbox" id="with_trashed">
                    <label class="form-check-label" for="with_trashed">Show Deleted</label>
                </div>
            </div>
            <table id="list_roles_table" class="dataTable table table-striped table-hover" data-url="{{ url('admin/member-roles/data') }}" data-params='{}'>
                <thead>
                    <tr>
                        <th data-name="name" data-order="primary-asc">Name</th>
                        <th data-name="is_default">Default</th>
                        <th data-name="user_count">Assigned Users</th>
                        <th data-name="created_at" data-o-sort="true">Date Created</th>
                        {!! Html::dataTablesActionColumn() !!}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@endsection