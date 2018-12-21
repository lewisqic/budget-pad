@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/activity') !!}

    <h2>
        <span>{!! Html::pageIcon('fal fa-file-alt') !!} Activity Log</span>
    </h2>

    <div class="content card">
        <div class="card-body">

            <div class="dataTable-filters">
                <select id="resource_type" class="form-control" style="width: 250px;">
                    <option value="">- Resource Type - </option>
                    @foreach ( $resource_types as $label => $type )
                    <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <table id="list_activity_table" class="dataTable table table-striped table-hover" data-url="{{ url('admin/activity/data') }}" data-params='{}'>
                <thead>
                    <tr>
                        <th data-name="affected_resource">Resource</th>
                        <th data-name="description">Action</th>
                        <th data-name="changes_made" data-order="false">Changes Made</th>
                        <th data-name="caused_by">Caused By</th>
                        <th data-name="created_at" data-o-sort="true" data-order="primary-desc">Date</th>
                        {!! Html::dataTablesActionColumn() !!}
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {

        $('#list_activity_table').on('draw.dt', function() {
            if ( $('#resource_type').val() === '' ) {
                $('#list_activity_table .dataTables_empty').html('Select a <strong>Resource Type</strong> to view activity log.');
            }
        });

        $('body').on('click', '.toggle-values', function(e) {
            e.preventDefault();
            var $values = $(this).closest('td').find('.long-values');
            if ( $values.is(':hidden') ) {
                $(this).html('hide values <i class="fal fa-angle-up"></i>');
                $values.show();
            } else {
                $(this).html('show values <i class="fal fa-angle-down"></i>');
                $values.hide();
            }
        });

    });
</script>
@endpush