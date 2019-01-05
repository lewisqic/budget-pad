@extends('layouts.admin')

@section('content')

{!! Breadcrumbs::render('admin/members') !!}

<div class="float-right">
    @if ( has_permission('admin.members.create') )
    <a href="{{ url('admin/members/create') }}" class="btn btn-primary open-sidebar"><i class="fal fa-plus-circle"></i> Add Member</a>
    @endif
</div>
<h2>
    <span>{!! Html::pageIcon('fal fa-users') !!} Members</span>
</h2>

<div class="content card">
    <div class="card-body">

        <div class="dataTable-filters">
            <div class="abc-checkbox abc-checkbox-primary form-check form-check-inline">
                <input type="checkbox" id="with_trashed">
                <label class="form-check-label" for="with_trashed">Show Deleted</label>
            </div>
        </div>
        <table id="list_members_table" class="dataTable table table-striped table-hover" data-url="{{ url('admin/members/data') }}" data-params='{}'>
            <thead>
            <tr>
                <th data-name="name_email">Name</th>
                <th data-name="company">Company</th>
                <th data-name="company_owner">Owner</th>
                <th data-name="status">Sub. Status</th>
                <th data-name="amount">Sub. Amount</th>
                <th data-name="created_at" data-o-sort="true" data-order="primary-desc">Date Joined</th>
                {!! Html::dataTablesActionColumn() !!}
            </tr>
            </thead>
            <tbody></tbody>
        </table>

    </div>
</div>

@endsection

@push('scripts')
    {!! Js::stripeConfig() !!}
    <script src="https://js.stripe.com/v3/"></script>
@endpush