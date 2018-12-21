@extends('layouts.admin')

@section('content')

{!! Breadcrumbs::render('admin/profile') !!}

<h2>
    <span>{!! Html::pageIcon('fal fa-user') !!} My Profile</span>
</h2>

<div class="content card">
    <div class="card-body">

        <form action="{{ url('admin/profile') }}" method="post" class="validate tabs labels-right" id="update_profile_form" enctype="multipart/form-data">
            <input type="hidden" name="ignore_permissions" value="1">
            {!! Html::hiddenInput(['method' => 'put']) !!}

            <ul class="nav nav-tabs page-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#personal" role="tab">Personal Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#credentials" role="tab">Login Credentials</a>
                </li>
            </ul>

            <div class="tab-content page-tabs-content">
                <div class="tab-pane fade show active" id="personal" role="tabpanel">

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">First Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ $auth_user->first_name }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Last Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ $auth_user->last_name }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="2" data-fv-stringlength-max="80">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Avatar</label>
                        <div class="col-sm-9">

                            @if ( $auth_user->avatar )
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="pr-7">
                                            <img src="{{ Storage::url($auth_user->avatar) }}" class="w-100">
                                        </div>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                                            <input type="checkbox" name="delete_avatar" id="delete_avatar" value="1">
                                            <label class="form-check-label" for="delete_avatar">Delete Image</label>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="file" name="avatar">
                                <div class="form-text text-muted font-13">Upload your profile image. Max file size: 3MB.</div>
                            @endif

                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="credentials" role="tabpanel">

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Email</label>
                        <div class="col-sm-9">
                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ $auth_user->email }}" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="5" data-fv-stringlength-max="64" data-fv-emailaddress="true">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-9 ml-auto">
                            <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                                <input type="checkbox" class="toggle-content" id="change_password" data-toggle=".password-fields">
                                <label class="form-check-label" for="change_password">Change Password</label>
                            </div>
                        </div>
                    </div>

                    <div class="password-fields ignore-validation hide">

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" id="user_password" class="form-control" placeholder="Password" value="" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Confirm Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" value="" data-fv-notempty="true" data-fv-stringlength="true" data-fv-stringlength-min="6" data-fv-identical="true" data-fv-identical-field="password" autocomplete="off">
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                </div>
            </div>


        </form>

    </div>
</div>


@endsection