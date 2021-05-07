<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title', 'update the post')

@section('content')
    <form action="{{ route('posts.update', ['post' => $post]) }}" method="POST">
        @csrf()
        @method('PUT')
        @include('posts.partials._form')
        <button type="submit" class="btn btn-primary my-3">Update the post</button>
    </form>
@endsection
