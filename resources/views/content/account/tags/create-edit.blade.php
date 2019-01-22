@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( isset($tag) )
    {!! Breadcrumbs::render('account/tags/edit', $tag) !!}
@else
    {!! Breadcrumbs::render('account/tags/create') !!}
@endif

<h2>
    <span>{!! Html::pageIcon('fal fa-tag') !!} {{ $title }} Tag <small>{{ $tag->name ?? '' }}</small></span>
</h2>

<div class="content card">
    <div class="card-body">

        <form action="{{ url('account/tags' . (isset($tag) ? '/' . $tag->id : '')) }}" method="post" class="validate tabs labels-right" id="create_edit_accountistrator_form">
            <input type="hidden" name="id" value="{{ $tag->id ?? '' }}">
            <input type="hidden" name="company_id" value="{{ $company->id }}">
            {!! Html::hiddenInput(['method' => isset($tag) ? 'put' : 'post']) !!}

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Name</label>
                <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" placeholder="Tag Name" value="{{ $tag->name ?? old('name') }}" data-fv-notempty="true" autofocus>
                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                    <a href="#" class="btn btn-secondary close-sidebar">Cancel</a>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection