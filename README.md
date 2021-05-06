<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project 

The purpose of this project is to build a simple but robust laravel-application 
experiencing major Laravel concepts 
* TDD 
* Accessors and Mutators With custom Cast classes 
* Lazy + Eager loading 
* Blade components 
* Overview of all relationships (1:1, 1:n, n:n) with polymorphic variants 
* Model Factories And Seeders with prompt interaction 
* Scopes (local and global)
* Caching 
* File Storage and Uploading 
* Mails 
* Api basics with Laravel ressources 
* Localisation 
* ... 

## Routing 
Some useful information about routing in Laravel 
(Of course i will use controllers later as the project will grow)

### Adding optional parameters 
```
Route::get('/recent-posts/{days_ago?}', function($daysAgo = 30){
    return 'Posts in the last '. $daysAgo . ' days ago'; 
})->name('posts.recent.index'); 
```

### Validating parameters 
```
Route::get('/posts/{id?}', function($id){
    return 'Post number : ' . $id; 
})->where(['id' => '[0-9]+'])->name('posts.show'); 
```

### Just do it globally ! 
As checking if the id is a very common task and is fllows the same pattern 
Go to the RouteServiceProvider and register the pattern (append to boot methode)

```
Route::pattern('id', '[0-9]+');
```
You can now get rid of the where statement in the route declaration ! 


