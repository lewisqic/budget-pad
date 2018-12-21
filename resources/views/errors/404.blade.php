@extends(\Auth::user() && \Auth::user()->type == 1 ? 'layouts.admin' : 'layouts.index')

@section('content')

    <div class="content card error-page">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-6 offset-3">

                    <div class="error-wrapper">

                        <h1 class="error-code">404</h1>

                        <hr class="my-7">

                        <h2>Sorry, Page Not Found</h2>
                        <p>{{ $exception->getMessage() }}</p>

                    </div>

                </div>
            </div>

        </div>
    </div>


@endsection