@extends('layouts.app')

@section('title','demo index page')

@section('content')
    <h1>Demo Index page </h1>
    <div class="content">
        @forelse($posts as $post)
            <section>
                <h2>{{ $post['title'] }}</h2>
                <p>{{ $post['content'] }}</p>
            </section>
        @empty
            <div>No post yet!</div>
        @endforelse
    </div>
@endsection
