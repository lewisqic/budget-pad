@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/administrators') !!}

    <div class="float-right">
        <a href="{{ url('admin/administrators/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Administrator</a>
    </div>
    <h2>
        <span>{!! Html::pageIcon('fal fa-users') !!} Administrators</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                    <input type="checkbox" id="with_trashed">
                    <label class="form-check-label" for="with_trashed">Show Deleted</label>
                </div>
            </div>
            <table id="list_administrators_table" class="dataTable table table-striped table-hover" data-url="{{ url('admin/administrators/data') }}" data-params='{}'>
                <thead>
                <tr>
                    <th data-name="name" data-order="primary-asc">Name</th>
                    <th data-name="email">Email</th>
                    <th data-name="roles">Roles</th>
                    <th data-name="superuser">Superuser</th>
                    <th data-name="created_at" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@endsection