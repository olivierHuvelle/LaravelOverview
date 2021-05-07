<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title', 'create a post')

@section('content')
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf()
        @include('posts.partials._form')
        <button type="submit" class="btn btn-primary my-3">Create the post</button>
    </form>
@endsection
