@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/expenses') !!}

    <div class="float-right">
        <a href="{{ url('account/expenses/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Expense</a>
    </div>
    <h2>
        <span>{!! Html::pageIcon('fal fa-file-invoice-dollar') !!} Expenses</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                    <input type="checkbox" id="with_trashed">
                    <label class="form-check-label" for="with_trashed">Show Deleted</label>
                </div>
            </div>
            <table id="list_expense_table" class="dataTable table table-striped table-hover" data-url="{{ url('account/expenses/data') }}" data-params='{}'>
                <thead>
                <tr>
                    <th data-name="amount">Amount</th>
                    <th data-name="notes">Notes</th>
                    <th data-name="category">Category</th>
                    <th data-name="tags">Tags</th>
                    <th data-name="date_at" data-o-sort="true" data-order="primary-desc">Date</th>
                    {!! Html::dataTablesActionColumn() !!}
                </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@endsection