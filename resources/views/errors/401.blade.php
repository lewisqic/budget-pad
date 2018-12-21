@extends('layouts.' . $error_layout)

@section('content')

    <div class="content card error-page">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-8 offset-2">

                    <div class="error-wrapper">

                        <h1 class="error-code">401</h1>

                        <hr class="my-7">

                        <h2>Sorry, you are not authorized to access this page.</h2>
                        <p class="text-danger">{{ $exception->getMessage() }}</p>

                    </div>

                </div>
            </div>

        </div>
    </div>


@endsection