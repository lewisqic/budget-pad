@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account/incomes') !!}

    <div class="float-right">
        <input type="hidden" class="category-id" value="{{ $category->id ?? 0 }}">
        <input type="hidden" class="start-date" value="{{ $dates[0] }}">
        <input type="hidden" class="end-date" value="{{ $dates[1] }}">
        <form action="{{ url('account/change-dates') }}" method="post" id="change_dates_form">
            <input type="hidden" name="start_date" value="">
            <input type="hidden" name="end_date" value="">
            {!! Html::hiddenInput(['method' => 'post']) !!}
            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div>
        </form>
    </div>

    <h2>
        <span>{!! Html::pageIcon('fal fa-money-bill') !!} {{ $category->name ?? '' }} Income Overview</span>
    </h2>

    <div class="content card">
        <div class="card-body chart-wrapper">

            <canvas id="data_chart" height="70"></canvas>

            <div class="row tile-data">
                <div class="col-sm-4">
                    <span class="text-success incomes">{{ Format::currency($tile_data['incomes']) }}</span>
                    <small>Income</small>
                </div>
                <div class="col-sm-4">
                    <span class="text-dark transactions">{{ $tile_data['transactions'] }}</span>
                    <small>Deposits</small>
                </div>
                <div class="col-sm-4">
                    <span class="text-primary avg">{{ Format::currency($tile_data['avg']) }}</span>
                    <small>Avg. Daily Income</small>
                </div>
            </div>

        </div>
    </div>


    <div class="float-right">
        <a href="{{ url('account/incomes/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Income</a>
    </div>
    <h3 class="mb-3">
        My Income Entries
    </h3>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                    <input type="checkbox" id="with_trashed">
                    <label class="form-check-label" for="with_trashed">Show Deleted</label>
                </div>
            </div>
            <table id="list_income_table" class="dataTable table table-striped table-hover" data-url="{{ url('account/incomes/data') }}" data-params='{"start_date": "{{ $dates[0] }}", "end_date": "{{ $dates[1] }}", "category_id": {{ isset($category->id) ? $category->id : 0 }}}'>
                <thead>
                    <tr>
                        <th data-name="category">Category</th>
                        <th data-name="amount">Amount</th>
                        <th data-name="date_at" data-o-sort="true" data-order="primary-desc">Date</th>
                        <th data-name="tags">Tags</th>
                        <th data-name="notes">Notes</th>
                        {!! Html::dataTablesActionColumn() !!}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@endsection

@push('scripts')

<script>
    var chart_name = 'Income';
    var chart_data = JSON.parse('{!! json_encode($chart_data) !!}');
</script>
<script src="{{ url('assets/js/modules/charting.js') }}"></script>

@endpush