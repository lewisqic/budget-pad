@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account') !!}

    <div class="float-right">
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
        <span>{!! Html::pageIcon('fal fa-tachometer-alt') !!} Your Financial Overview</span>
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

    <div class="float-right">
        <h4 class="d-inline-block">
            <small>Spent:</small> <span class="text-danger">{{ Format::currency($category_data['totals']['expense']['spent']) }}</span>
        </h4>
        <h4 class="d-inline-block ml-5">
            <small>Budgeted:</small> <span class="text-success">{{ Format::currency($category_data['totals']['expense']['budgeted']) }}</span>
        </h4>
    </div>
    <h3 class="mb-3">
        Where's Your Money Going?
    </h3>
    <div class="content card">
        <div class="card-body">

            <div class="expandable">
                <h4>
                    Fixed Expenses
                    <i class="fal fa-lg fa-angle-down ml-2"></i>
                </h4>
                <div class="content">

                    @if ( !empty($category_data['data']['fixed']) )

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 25%;">Category</th>
                                    <th style="width: 25%;">Expenses</th>
                                    <th style="width: 25%;">Budgeted</th>
                                    <th style="width: 25%;">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $category_data['data']['fixed'] as $data )
                                <tr>
                                    <td><a href="{{ url('account/expenses/category/' . $data['category']->id) }}">{{ $data['category']->name }}</a></td>
                                    <td>{{ Format::currency($data['expenses']) }}</td>
                                    <td>{{ Format::currency($data['budgeted']) }}</td>
                                    <td class="text-{{ $data['percent'] <= 100 ? 'success' : 'danger' }}">{{ $data['percent'] }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>
                                        <strong>Total</strong>
                                    </td>
                                    <td>
                                        <strong>{{ Format::currency($category_data['totals']['fixed']['expenses']) }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ Format::currency($category_data['totals']['fixed']['budgeted']) }}</strong>
                                    </td>
                                    <td class="text-{{ $category_data['totals']['fixed']['percent'] <= 100 ? 'success' : 'danger' }}">
                                        <strong>{{ $category_data['totals']['fixed']['percent'] }}%</strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                    @else

                        <em class="text-muted">No fixed expense data found</em>

                    @endif

                </div>
            </div>

            <div class="expandable">
                <h4>
                    Discretionary Expenses
                    <i class="fal fa-lg fa-angle-down ml-2"></i>
                </h4>
                <div class="content">

                    @if ( !empty($category_data['data']['discretionary']) )

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width: 25%;">Category</th>
                                <th style="width: 25%;">Expenses</th>
                                <th style="width: 25%;">Budgeted</th>
                                <th style="width: 25%;">%</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ( $category_data['data']['discretionary'] as $data )
                                <tr>
                                    <td><a href="{{ url('account/expenses/category/' . $data['category']->id) }}">{{ $data['category']->name }}</a></td>
                                    <td>{{ Format::currency($data['expenses']) }}</td>
                                    <td>{{ Format::currency($data['budgeted']) }}</td>
                                    <td class="text-{{ $data['percent'] <= 100 ? 'success' : 'danger' }}">{{ $data['percent'] }}%</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>
                                    <strong>Total</strong>
                                </td>
                                <td>
                                    <strong>{{ Format::currency($category_data['totals']['discretionary']['expenses']) }}</strong>
                                </td>
                                <td>
                                    <strong>{{ Format::currency($category_data['totals']['discretionary']['budgeted']) }}</strong>
                                </td>
                                <td class="text-{{ $category_data['totals']['discretionary']['percent'] <= 100 ? 'success' : 'danger' }}">
                                    <strong>{{ $category_data['totals']['discretionary']['percent'] }}%</strong>
                                </td>
                            </tr>
                            </tfoot>
                        </table>

                    @else

                        <em class="text-muted">No discretionary expense data found</em>

                    @endif

                </div>
            </div>

            <div class="expandable">
                <h4>
                    Income
                    <i class="fal fa-lg fa-angle-down ml-2"></i>
                </h4>
                <div class="content">

                    @if ( !empty($category_data['data']['income']) )

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width: 25%;">Category</th>
                                <th style="width: 25%;">Income</th>
                                <th style="width: 25%;"></th>
                                <th style="width: 25%;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ( $category_data['data']['income'] as $data )
                                <tr>
                                    <td><a href="{{ url('account/incomes/category/' . $data['category']->id) }}">{{ $data['category']->name }}</a></td>
                                    <td>{{ Format::currency($data['incomes']) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td>
                                    <strong>Total</strong>
                                </td>
                                <td>
                                    <strong>{{ Format::currency($category_data['totals']['income']['incomes']) }}</strong>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>

                    @else

                        <em class="text-muted">No income data found</em>

                    @endif

                </div>
            </div>

        </div>
    </div>

    <h3 class="mb-3">
        Track Tags
    </h3>

    <div class="content card">
        <div class="card-body">

            @if ( empty($tag_data) )
                <em class="text-muted">No tag data found</em>
            @endif

            @foreach ( $tag_data as $tag )
            <div class="expandable">
                <h4>
                    {{ $tag['tag'] }}
                    <i class="fal fa-lg fa-angle-down ml-2"></i>
                </h4>
                <div class="content">
                    <canvas class="tags_chart" height="25" data-incomes="{{ $tag['incomes'] }}" data-expenses="{{ $tag['expenses'] }}"></canvas>
                </div>
            </div>
            @endforeach

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