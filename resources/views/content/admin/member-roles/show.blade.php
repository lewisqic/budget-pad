@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.admin')

@section('content')

{!! Breadcrumbs::render('admin/member-roles/show', $role) !!}

<div class="float-right">
    @if ( has_permission('admin.member-roles.edit') )
    <a href="{{ url('admin/member-roles/' . $role->id . '/edit?_ajax=false&_redir=' . urlencode(url('admin/member-roles/' . $role->id))) }}" class="btn btn-primary open-sidebar"><i class="fa fa-edit"></i> Edit</a>
    @endif
    @if ( has_permission('admin.member-roles.destroy') )
    <form action="{{ url('admin/member-roles/' . $role->id) }}" method="post" class="validate d-inline ml-2" id="delete_form">
        {!! \Html::hiddenInput(['method' => 'delete']) !!}
        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
    </form>
    @endif
</div>

<h2>
    <span>{!! Html::pageIcon('fal fa-key') !!} {{ $role->name }} <small>Role</small></span>
</h2>

<div class="content card">
    <div class="card-body labels-right">


        <ul class="nav nav-tabs hash-tabs page-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#show_details" role="tab">Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#show_users" role="tab">Users</a>
            </li>
        </ul>

        <div class="tab-content page-tabs-content">
            <div class="tab-pane fade show active" id="show_details" role="tabpanel">

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Name:</label>
                    <div class="col-sm-9 form-control-static">
                        {{ $role->name }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Default:</label>
                    <div class="col-sm-9 form-control-static">
                        {{ $role->is_default ? 'Yes' : 'No' }}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Permissions:</label>
                    <div class="col-sm-9 form-control-static">
                        @foreach ( $role_permissions as $group => $actions )
                            <div class="{{ !$loop->first ? 'mt-3' : '' }}">{{ $group }}</div>
                            @foreach ( $actions as $label )
                                <div class="ml-4"><small>{{ $label }}</small></div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <br>

                @if ( $role->updated_at )
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Last Updated:</label>
                        <div class="col-sm-9 form-control-static">
                            {{ $role->updated_at->toDayDateTimeString() }}
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="col-form-label col-sm-3">Date Created:</label>
                    <div class="col-sm-9 form-control-static">
                        {{ $role->created_at->toDayDateTimeString() }}
                    </div>
                </div>

            </div>
            <div class="tab-pane fade" id="show_users" role="tabpanel">

                <table id="list_role_users_table" class="dataTable table table-striped table-hover" data-url="{{ url('admin/members/data') }}" data-params='{"role_id": "{{ $role->id }}"}'>
                    <thead>
                        <tr>
                            <th data-name="first_name" data-order="primary-asc">First Name</th>
                            <th data-name="last_name">Last Name</th>
                            <th data-name="email">Email</th>
                            <th data-name="last_login" data-o-sort="true">Last Login</th>
                            <th data-name="created_at" data-o-sort="true">Date Created</th>
                            {!! Html::dataTablesActionColumn(true) !!}
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>


    </div>

</div>

@endsection