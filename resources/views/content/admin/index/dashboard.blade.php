@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin') !!}

    <h2>
        <span>{!! Html::pageIcon('fal fa-tachometer-alt') !!} Admin Dashboard</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <h4 class="text-muted">Subscriptions</h4>

            <div class="row my-5">

                <div class="col-sm-3">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Active Subscribers</h5>
                            <h1 class="text-success font-weight-bold">{{ $subscribers['Active'] }}</h1>
                        </div>
                    </div>

                </div>
                <div class="col-sm-3">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Pending Cancelation</h5>
                            <h1 class="text-warning font-weight-bold">{{ $subscribers['Pending Cancelation'] }}</h1>
                        </div>
                    </div>

                </div>
                <div class="col-sm-3">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Canceled Subscriptions</h5>
                            <h1 class="text-danger font-weight-bold">{{ $subscribers['Canceled'] }}</h1>
                        </div>
                    </div>

                </div>
                <div class="col-sm-3">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Avg Subscription Length</h5>
                            <h1 class="text-primary font-weight-bold">{{ $average_days }} <small class="text-muted font-20">Days</small></h1>
                        </div>
                    </div>

                </div>

            </div>

            <hr class="my-5">

            <h4 class="text-muted">Revenue</h4>

            <div class="row my-5">

                <div class="col-sm-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Monthly Income</h5>
                            <h2 class="text-success font-weight-bold">{!! Format::currency($revenue['monthly']) . '<small class="text-muted">/month</small>' !!}</h2>
                        </div>
                    </div>

                </div>
                <div class="col-sm-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Yearly Income</h5>
                            <h2 class="text-success font-weight-bold">{!! Format::currency($revenue['yearly']) . '<small class="text-muted">/year</small>' !!}</h2>
                        </div>
                    </div>

                </div>
                <div class="col-sm-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <h5>Year to Date</h5>
                            <h2 class="text-success font-weight-bold">{!! Format::currency($revenue['year_to_date']) !!}</h2>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>


@endsection

@push('scripts')


@endpush