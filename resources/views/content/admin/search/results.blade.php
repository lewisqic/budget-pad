@extends('layouts.admin')

@section('content')

    {!! Breadcrumbs::render('admin/search') !!}

    <h2>
        <span>{!! Html::pageIcon('fal fa-search') !!} Search Results <small>{{ Request::input('keywords') }}</small></span>
    </h2>

    @php $has_results = false @endphp
    @foreach ( $results as $result )
        @if ( count($result['results']) )
            @php $has_results = true @endphp


            <div class="content card mb-4">
                <div class="card-body">

                    <h5><i class="{{ $result['icon'] }}"></i> {{ $result['title'] }}</h5>

                    <table class="table table-striped table-sm">
                        <thead>
                        @foreach ( $result['columns'] as $label => $key )
                            <th style="width: {{ 100 / count($result['columns']) }}%;">{{ $label }}</th>
                        @endforeach
                        </thead>
                        <tbody>
                        @foreach ( $result['results'] as $row )
                            <tr>
                            @foreach ( $result['columns'] as $label => $key )
                                <td>
                                    {!! isset($result['link'][$key]) ? '<a href="' . str_replace('{id}', $row['id'], $result['link'][$key]) . '">' : '' !!}
                                    {!! $key == 'created_at' ? $row[$key]->toDayDateTimeString() : \Html::yesOrNo($row[$key]) !!}
                                    {!! isset($result['link'][$key]) ? '</a>' : '' !!}
                                </td>
                            @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>


        @endif
    @endforeach

    @if ( !$has_results )
        <div class="content card">
            <div class="card-body">

                <h5 class="py-5 text-center text-muted">
                    <em>No Results Found</em>
                </h5>

            </div>
        </div>
    @endif


@endsection