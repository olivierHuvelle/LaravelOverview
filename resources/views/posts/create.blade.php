<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title', 'create a post')

@section('content')
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf()
        @include('posts.partials._form')
    </form>
@endsection
