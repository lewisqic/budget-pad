@extends('layouts.' . $error_layout)

@section('content')

    <div class="content card error-page">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-8 offset-2">

                    <div class="error-wrapper">

                        <h1 class="error-code">403</h1>
                        <hr class="my-7">
                        <h2>{{ $exception->getMessage() }}</h2>

                    </div>

                </div>
            </div>

        </div>
    </div>


@endsection