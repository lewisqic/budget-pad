@extends('layouts.account')

@section('content')


    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Customers</a></li>
        <li class="breadcrumb-item"><a href="#">John Doe</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="float-right">
        <a href="#" class="btn btn-primary"><i class="fal fa-plus"></i> Add User</a>
    </div>
    <h2><i class="fal fa-cogs"></i> Settings</h2>

    <div class="content card">
        <div class="card-body">

            <div class="nav nav-tabs page-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Contact</a>
            </div>
            <div class="tab-content page-tabs-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <h4 class="text-primary">Home Content</h4>

                    <div class="row">
                        <div class="col-lg-2">
                            <a href="#" class="btn btn-primary mt-1">Primary</a>
                        </div>
                        <div class="col-lg-2">
                            <a href="#" class="btn btn-secondary mt-2">Secondary</a>
                        </div>
                        <div class="col-lg-2">
                            <a href="#" class="btn btn-success text-white mt-3">Success</a>
                        </div>
                        <div class="col-lg-2">
                            <a href="#" class="btn btn-info mt-4">Info</a>
                        </div>
                        <div class="col-lg-2">
                            <a href="#" class="btn btn-warning text-white mt-5">Warning</a>
                        </div>
                        <div class="col-lg-2">
                            <a href="#" class="btn btn-danger mt-6">Danger</a>
                        </div>
                    </div>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi bibendum pretium fermentum. Sed dapibus sagittis gravida. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Praesent non tempor augue. Curabitur viverra mauris quis nulla luctus eu tristique quam pretium. Donec interdum, ligula ac aliquet iaculis, ante eros iaculis urna, ut scelerisque urna augue vestibulum enim. Etiam sollicitudin, velit eu tempor elementum, neque arcu viverra diam, et sodales lacus massa vitae tellus. Maecenas arcu nunc, consequat at volutpat eget, vestibulum id arcu. Etiam risus arcu, eleifend at volutpat nec, elementum nec tortor. Pellentesque nec tortor vitae nunc laoreet sollicitudin ut eu elit. Phasellus non arcu ut velit lacinia fermentum. Mauris vitae dui eget enim lobortis hendrerit facilisis ut tortor. Sed vitae lorem vel dui rhoncus dapibus at a purus. In quam enim, malesuada in lacinia sed, lobortis vitae est. Maecenas ullamcorper mattis pulvinar. Integer tempor rhoncus enim, quis condimentum ipsum feugiat a. </p>

                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

                    <div class="row vertical-tabs">
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
                    </div>

                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">



                </div>
            </div>

        </div>
    </div>


@endsection

@push('scripts')


@endpush