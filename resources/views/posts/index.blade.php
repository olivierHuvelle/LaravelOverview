@extends('layouts.app')

@section('title','posts')

@section('content')
    <h1>Post Index  </h1>
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
