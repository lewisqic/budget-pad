@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account') !!}

    <div class="float-right">
        <input type="hidden" class="start-date" value="{{ $initial_dates[0] }}">
        <input type="hidden" class="end-date" value="{{ $initial_dates[1] }}">
        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <i class="fa fa-caret-down"></i>
        </div>
    </div>

    <h2>
        <span>{!! Html::pageIcon('fal fa-tachometer-alt') !!} My Financial Overview</span>
    </h2>

    <div class="content card">
        <div class="card-body chart-wrapper">

            <canvas id="data_chart" height="70"></canvas>

            <div class="row tile-data">
                <div class="col-sm-2">
                    <span class="text-{{ $tile_data['incomes'] > 0 ? 'success' : 'danger' }} incomes">{{ Format::currency($tile_data['incomes']) }}</span>
                    <small>Income</small>
                </div>
                <div class="col-sm-2">
                    <span class="text-danger expenses">{{ Format::currency($tile_data['expenses']) }}</span>
                    <small>Expenses</small>
                </div>
                <div class="col-sm-2">
                    <span class="text-{{ $tile_data['net'] > 0 ? 'success' : 'danger' }} net">{{ Format::currency($tile_data['net']) }}</span>
                    <small>Net Income</small>
                </div>
                <div class="col-sm-2">
                    <span class="text-{{ $tile_data['savings'] > 0 ? 'success' : 'danger' }} savings">{{ $tile_data['savings'] }}%</span>
                    <small>Savings Rate</small>
                </div>
                <div class="col-sm-2">
                    <span class="text-dark transactions">{{ $tile_data['transactions'] }}</span>
                    <small>Transactions</small>
                </div>
                <div class="col-sm-2">
                    <span class="text-primary avg">{{ Format::currency($tile_data['avg']) }}</span>
                    <small>Avg. Daily Spend</small>
                </div>
            </div>

        </div>
    </div>


@endsection

@push('scripts')

    <script>
        var chart_name = 'Spend';
        var chart_data = JSON.parse('{!! json_encode($chart_data) !!}');
    </script>
    <script src="{{ url('assets/js/modules/charting.js') }}"></script>

@endpush