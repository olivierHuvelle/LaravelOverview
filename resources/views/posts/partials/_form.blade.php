<?php /** @var \App\Models\Post $post  */ ?>

<div class="form-group my-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('name', $post->title) }}">
    @error('title')
    <div class="invalid-feedback">
        {{$message}}
    </div>
    @enderror
</div>

<div class="form-group my-3">
    <label for="content">Content</label>
    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5">{{ old('description', $post->content) }}</textarea>
    @error('content')
    <div class="invalid-feedback">
        {{$message}}
    </div>
    @enderror
</div>

<button type="submit" class="btn btn-primary my-3">Create the post</button>

