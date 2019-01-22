@extends(\Request::ajax() ? 'layouts.ajax' : 'layouts.account')

@section('content')

@if ( isset($income) )
    {!! Breadcrumbs::render('account/incomes/edit', $income) !!}
@else
    {!! Breadcrumbs::render('account/incomes/create') !!}
@endif

<h2>
    <span>{!! Html::pageIcon('fal fa-money-bill') !!} {{ $title }} Income <small>{{ $income->notes ?? '' }}</small></span>
</h2>

<div class="content card">
    <div class="card-body">

        @if ( !$categories->isEmpty() )

        <form action="{{ url('account/incomes' . (isset($income) ? '/' . $income->id : '')) }}" method="post" class="validate tabs labels-right" id="create_edit_accountistrator_form">
            <input type="hidden" name="id" value="{{ $income->id ?? '' }}">
            <input type="hidden" name="company_id" value="{{ $company->id }}">
            {!! Html::hiddenInput(['method' => isset($income) ? 'put' : 'post']) !!}

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Amount</label>
                <div class="col-sm-9">
                    <input type="text" name="amount" class="form-control" placeholder="0.00" value="{{ $income->amount ?? old('amount') }}" data-fv-notempty="true" data-fv-numeric="true" autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Date</label>
                <div class="col-sm-9">
                    <input type="text" name="date_at" class="form-control datepicker" placeholder="mm/dd/yyyy" value="{{ isset($income) && $income->date_at ? $income->date_at->format('m/d/Y') : date('m/d/Y') }}" data-fv-notempty="true">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Notes</label>
                <div class="col-sm-9">
                    <input type="text" name="notes" class="form-control" placeholder="Notes" value="{{ $income->notes ?? old('notes') }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Category</label>
                <div class="col-sm-9 mt-1">

                    <select name="category_id" class="form-control" data-fv-notempty="true">
                        <option value="">- Select Category -</option>
                        @foreach ( $categories as $category )
                        <option value="{{ $category->id }}" {{ isset($income) && $income->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="form-group row">
                <label class="col-form-label col-sm-3">Tags</label>
                <div class="col-sm-9 mt-1">

                    <select name="tags[]" class="selectize-tags" placeholder="Select Tag(s)..." multiple>
                        <option value="">Select tag(s)...</option>
                        @foreach ( $tags as $tag )
                            <option value="{{ $tag->id }}" {{ isset($income) && $income->tags->contains('id', $tag->id) ? 'selected' : '' }}>{{ $tag->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>

            <div class="form-group row mt-5">
                <div class="col-sm-9 ml-auto">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-notch fa-spin fa-lg'></i>"><i class="fa fa-check"></i> Save</button>
                    <a href="#" class="btn btn-secondary close-sidebar">Cancel</a>
                </div>
            </div>

        </form>

        @else

            <div class="alert alert-danger alert-alt">
                Please create at least one category before adding income.
            </div>

        @endif

    </div>
</div>

@endsection

@push('scripts')
    <script>

        $('.selectize-tags').selectize({

        });

    </script>
@endpush