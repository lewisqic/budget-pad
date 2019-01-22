@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/categories') !!}

    <div class="float-right">
        <a href="{{ url('account/categories/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Category</a>
    </div>
    <h2>
        <span>{!! Html::pageIcon('fal fa-sitemap') !!} Categories</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                    <input type="checkbox" id="with_trashed">
                    <label class="form-check-label" for="with_trashed">Show Deleted</label>
                </div>
            </div>
            <table id="list_categories_table" class="dataTable table table-striped table-hover" data-url="{{ url('account/categories/data') }}" data-params='{}'>
                <thead>
                <tr>
                    <th data-name="name" data-order="primary-asc">Name</th>
                    <th data-name="budget" data-order="primary-asc">Budget</th>
                    <th data-name="category_type" data-order="primary-asc">Category Type</th>
                    <th data-name="amount_type" data-order="primary-asc">Amount Type</th>
                    <th data-name="created_at" data-o-sort="true">Date Created</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
                </thead>
                <tbody></tbody>
            </table>


        </div>
    </div>

@endsection