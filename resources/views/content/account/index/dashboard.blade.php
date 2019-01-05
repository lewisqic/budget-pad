@extends('layouts.account')

@section('content')

    {!! Breadcrumbs::render('account') !!}

    <h2>
        <span>{!! Html::pageIcon('fal fa-tachometer-alt') !!} Account Dashboard</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            content here

        </div>
    </div>


@endsection

@push('scripts')


@endpush