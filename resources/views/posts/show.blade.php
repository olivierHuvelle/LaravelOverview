<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <h1 class="mx-0 mb-3">{{ $post->title }}</h1>
    <p>
        Added {{ $post->created_at->diffForHumans() }}
        @if(now()->diffInMinutes($post->created_at) < 10)
            <span class="badge bg-success">New</span>
        @endif
    </p>

    <div class="content">{{ $post->content }}</div>
@endsection
