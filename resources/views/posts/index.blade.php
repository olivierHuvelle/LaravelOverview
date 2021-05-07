<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title','posts')

@section('content')
    <h1 class="text-center">Post Index </h1>

    @forelse($posts as $post)
        <section class="row d-flex align-items-center">
            <h2 class="col-12 col-md-8 my-0">
                <a href="{{ route('posts.show', ['post' => $post]) }}"> {{ $post->title }}</a>
            </h2>
            <div class="col-12 col-md-4">
                <a href="{{ route('posts.edit', ['post' => $post]) }}" class="btn btn-outline-success">Update</a>

                <form action="{{ route('posts.destroy', ['post' => $post]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger my-3">Delete</button>
                </form>
            </div>
        </section>
    @empty
        <div>No post yet!</div>
    @endforelse
@endsection
