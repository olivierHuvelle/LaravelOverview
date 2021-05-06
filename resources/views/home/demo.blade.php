@extends('layouts.app')

@section('title','homepage')

@section('content')
    <h1>{{ $post['title'] }}</h1>

    <div>
        @if($post['is_new'])
            <strong>This is a new post!</strong>
        @else
            <u>This is not a new post !</u>
        @endif
    </div>

    <div class="content">{{ $post['content'] }}</div>
@endsection
