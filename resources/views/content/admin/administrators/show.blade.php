@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')

{!! Breadcrumbs::render('admin/administrators/show', $user) !!}

<div class="float-right">
    @if ( has_permission('admin.administrators.edit') )
    <a href="{{ url('admin/administrators/' . $user->id . '/edit?_ajax=false&_redir=' . urlencode(url('admin/administrators/' . $user->id))) }}" class="btn btn-primary open-sidebar"><i class="fa fa-edit"></i> Edit</a>
    @endif
    @if ( has_permission('admin.administrators.destroy') )
    <form action="{{ url('admin/administrators/' . $user->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
        {!! \Html::hiddenInput(['method' => 'delete']) !!}
        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
    </form>
    @endif
</div>

<h2>
    <span>{!! Html::pageIcon('fal fa-user-tie') !!} {{ $user->name }} <small>Administrator</small></span>
</h2>

<div class="content card">
    <div class="card-body labels-right">


        <ul class="nav nav-tabs hash-tabs page-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#show_details" role="tab">Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#show_permissions" role="tab">Roles/Permissions</a>
            </li>
        </ul>

        <div class="tab-content page-tabs-content">
            <div class="tab-pane fade show active" id="show_details" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">First Name:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->first_name }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Last Name:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->last_name }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Email:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->email }}
                    </div>
                </div>

                <br>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Last Login:</label>
                    <div class="col-sm-10 form-control-static">
                        {!! $user->last_login ? $user->last_login->toDayDateTimeString() : '<em>no logins</em>' !!}
                    </div>
                </div>

                @if ( $user->updated_at )
                    <div class="form-group row">
                        <label class="col-form-label col-sm-2">Last Updated:</label>
                        <div class="col-sm-10 form-control-static">
                            {{ $user->updated_at->toDayDateTimeString() }}
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Date Created:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->created_at->toDayDateTimeString() }}
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="show_permissions" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Role(s):</label>
                    <div class="col-sm-10 form-control-static">
                        @if ( $user->roles )
                            @foreach ( $user->roles as $role )
                                <div><a href="{{ url('admin/administrator-roles/' . $role['id']) }}">{{ $role['name'] }}</a></div>
                            @endforeach
                        @else
                            <em class="text-muted">no role set</em>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Superuser:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->superuser ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Custom Permissions:</label>
                    <div class="col-sm-10 form-control-static">
                        {{ $user->custom_permissions ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-2">Permissions:</label>
                    <div class="col-sm-10 form-control-static">
                        @if ( $user_permissions )
                            @foreach ( $user_permissions as $group => $actions )
                                <div class="{{ !$loop->first ? 'mt-3' : '' }}">{{ $group }}</div>
                                @foreach ( $actions as $label )
                                    <div class="ml-4"><small>{{ $label }}</small></div>
                                @endforeach
                            @endforeach
                        @else
                            <em class="text-muted">no custom permissions set</em>
                        @endif
                    </div>
                </div>

            </div>
        </div>


    </div>

</div>

@endsection