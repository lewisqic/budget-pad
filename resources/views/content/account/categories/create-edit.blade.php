@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( isset($category) )
    {!! Breadcrumbs::render('account/categories/edit', $category) !!}
@else
    {!! Breadcrumbs::render('account/categories/create') !!}
@endif

<h2>
    <span>{!! Html::pageIcon('fal fa-sitemap') !!} {{ $title }} Category <small>{{ $category->name ?? '' }}</small></span>
</h2>

<div class="content card">
    <div class="card-body">

        <form action="{{ url('account/categories' . (isset($category) ? '/' . $category->id : '')) }}" method="post" class="validate tabs labels-right" id="create_edit_accountistrator_form">
            <input type="hidden" name="id" value="{{ $category->id ?? '' }}">
            <input type="hidden" name="company_id" value="{{ $company->id }}">
            {!! Html::hiddenInput(['method' => isset($category) ? 'put' : 'post']) !!}

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Name</label>
                <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $category->name ?? old('name') }}" data-fv-notempty="true" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Monthly Budget</label>
                <div class="col-sm-9">
                    <input type="text" name="budget" class="form-control" placeholder="0.00" value="{{ $category->budget ?? old('budget') }}" data-fv-numeric="true">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Category Type</label>
                <div class="col-sm-9 form-control-static">
                    <div class="abc-radio abc-radio-primary form-check-inline">
                        <input type="radio" name="category_type" id="category_type_expense" value="expense" checked>
                        <label for="category_type_expense" class="form-check-label">Expense</label>
                    </div>
                    <div class="abc-radio abc-radio-primary form-check-inline">
                        <input type="radio" name="category_type" id="category_type_income" value="income">
                        <label for="category_type_income" class="form-check-label">Income</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Amount Type</label>
                <div class="col-sm-9 form-control-static">
                    <div class="abc-radio abc-radio-primary form-check-inline">
                        <input type="radio" name="amount_type" id="amount_type_fixed" value="fixed" checked>
                        <label for="amount_type_fixed" class="form-check-label">Fixed</label>
                    </div>
                    <div class="abc-radio abc-radio-primary form-check-inline">
                        <input type="radio" name="amount_type" id="amount_type_discretionary" value="discretionary">
                        <label for="amount_type_discretionary" class="form-check-label">Discretionary</label>
                    </div>
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