@extends('layouts.index')

@section('title', 'BudgetPad | ' . (isset($post) ? ucwords($post->title) : 'Blog'))

@section('content')

    @if ( isset($post) )

        <h2 class="my-7">{{ $post->title }}</h2>

        <hr class="my-7">

        <div>
            {!! $post->content !!}
        </div>

        <div class="mt-7">
            <em class="text-muted font-14">{{ $post->created_at->format('F jS, Y') }}</em>
        </div>

    @else

        <h1 class="my-7">Blog</h1>


        @foreach ( $posts as $post )

            <hr class="my-7">

            <h3><a href="{{ url('blog/p/' . $post->ID) }}">{{ $post->title }}</a></h3>

            <div>
                @if ( !empty($post->excerpt) )
                    {!! $post->excerpt !!}
                @else
                    {!! mb_strimwidth(str_replace('<!-- wp:paragraph -->', '', str_replace('<!-- /wp:paragraph -->', '', $post->content)), 0, 500, "...") !!}
                @endif
                <div class="mt-4">
                    <em class="text-muted font-14">{{ $post->created_at->format('F jS, Y') }}</em>
                </div>
            </div>

        @endforeach

        <hr class="my-7">

        <div>
            {{ $posts->links() }}
        </div>

    @endif


@endsection