@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/tags') !!}

    <div class="float-right">
        <a href="{{ url('account/tags/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Tag</a>
    </div>
    <h2>
        <span>{!! Html::pageIcon('fal fa-tags') !!} Tags</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                    <input type="checkbox" id="with_trashed">
                    <label class="form-check-label" for="with_trashed">Show Deleted</label>
                </div>
            </div>
            <table id="list_tags_table" class="dataTable table table-striped table-hover" data-url="{{ url('account/tags/data') }}" data-params='{}'>
                <thead>
                <tr>
                    <th data-name="name" data-order="primary-asc">Name</th>
                    <th data-name="created_at" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@endsection