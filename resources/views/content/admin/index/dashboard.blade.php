@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin') !!}

    <h2>
        <span>{!! Html::pageIcon('fal fa-tachometer-alt') !!} Admin Dashboard</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <p>
                {Placeholder for future data}
            </p>

            {{--<div class="row vertical-tabs">
                <div class="col-lg-2">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" id="v-home-tab" data-toggle="pill" href="#v-home" role="tab" aria-controls="v-home" aria-selected="true">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="v-profile-tab" data-toggle="pill" href="#v-profile" role="tab" aria-controls="v-profile" aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="v-messages-tab" data-toggle="pill" href="#v-messages" role="tab" aria-controls="v-messages" aria-selected="false">Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="v-settings-tab" data-toggle="pill" href="#v-settings" role="tab" aria-controls="v-settings" aria-selected="false">Settings</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-10">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="v-home" role="tabpanel" aria-labelledby="v-home-tab">
                            <h4>Home</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>
                        </div>
                        <div class="tab-pane fade" id="v-profile" role="tabpanel" aria-labelledby="v-profile-tab">
                            <h4>Profile</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci cum, dolore ipsa ipsum nisi nostrum qui quia repudiandae rerum voluptates!</p>
                        </div>
                        <div class="tab-pane fade" id="v-messages" role="tabpanel" aria-labelledby="v-messages-tab">...</div>
                        <div class="tab-pane fade" id="v-settings" role="tabpanel" aria-labelledby="v-settings-tab">
                            <h4>Settings</h4>
                            <div class="mt-5">
                                <div class="form-group">
                                    <label class="hide">Test</label>
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                            <p>
                                <a href="#">Test Link!</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>--}}

        </div>
    </div>


@endsection

@push('scripts')


@endpush