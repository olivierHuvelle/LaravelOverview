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

## How to follow this step by step project 
back to the trees ... juste follow the git branches which have this schema number_title i.e 1_routing 2_template_and_views 

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

## Templating and Views 
### General information
Blade templates are compiled and cached so almost zero overhead to your app. 
Don't forget to name your blade template as the route that calling it (Clean code)

### Template inheritance 
When creating an app with blade your can split your code several ways 
* option1 : create templates and use the blade inheritance 
* option2 : create blade components 

I will cover both, i personally use templates to manage big  chunks of code and components as small re-usable snippets 

* views 
    * home > index.blade.php 
    * layouts > app.blade.php (main template)
    
**app.blade.php**
```
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>LaravelOverview : | @yield('title')</title>
    </head>
    <body>
        <main>
            @yield('content')
        </main>
    </body>
</html>
```

**index.blade.php**
```
@extends('layouts.app')

@section('title','homepage')

@section('content')
    <h1>Homepage</h1>
@endsection
```

As you can see template inheritance is very easy and convenient ! 

### Refactoring web.php 
As we have some dummy routes which only render views we can do it as so 
```
Route::name('home.')->group(function(){
    Route::view('/', 'home.index')->name('index');
    Route::view('/contact', 'home.contact')->name('contact');
});
```
This as some advantages :
* route - name prefix 
* no allowed controller 
* clean code still ! 


###Transmit data to blade file 
As it is only for demonstration purpose i wont implement this code on the repo 

**Demo routing with closure**
```
Route::get('/demo/{id}', function($id){
    $posts = [
        1 => [
            'title' => 'title 1',
            'content' => 'content 1'
        ],
        2 => [
            'title' => 'title 2',
            'content' => 'content 2'
        ],
        3 => [
            'title' => 'title 3',
            'content' => 'content 3'
        ]
    ];
    abort_if(!isset($posts[$id]), 404);

    return view('home.demo', ['post' => $posts[$id]]);
});
```
Notice 
* the use of $id variable from route to closure 
* abort_if (condition, error_code) 

**home.blade.php**
```
@extends('layouts.app')

@section('title','homepage')

@section('content')
    <h1>{{ $post['title'] }}</h1>
    <div class="content">
        {{ $post['content'] }}
    </div>
@endsection
```

Notice the mustache syntax , it is escaped and so secure :) 

### Conditional rendering
```
@if(condition)
    code to display 
@elseif(condition) 
    code to display 
@else 
    code to display
```

As a example let's add is_new key to our $posts variable 

```
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
```

<p><a href="https://laravel.com/docs/8.x/blade">Check out the blade documentation</a></p>

There are many useful blade directives , i won't cover them all . It is as always possible to create your own directives ! 

```
@unless(condition_to_be_false) 
    .... 
@endunless 

@isset($variable_name) 
    ... 
@endisset 
```

### Loops 
```
    @foreach($variables as $variable) 
        ... do something with $variable 
    @endforeach

    @forelse($variables as $variable) 
        ... do something with $variable 
    @empty 
        ... do something when $variables is empty 
    @endforelse 

    @for($i=0: $i<10; $i++) 
        instruction 
    @endfor 

    etc ... 

    @break(condition) , @continue(condition)  and $loop special variable 
```

As an example let's refactore our dummy code 

**web.php**
```
$posts = [
    1 => [
        'title' => 'title 1',
        'content' => 'content 1',
        'is_new' => true
    ],
    2 => [
        'title' => 'title 2',
        'content' => 'content 2',
        'is_new' => false
    ],
    3 => [
        'title' => 'title 3',
        'content' => 'content 3',
        'is_new' => false
    ]
];

Route::get('/demo/{id}', function($id) use ($posts){

    abort_if(!isset($posts[$id]), 404);
    return view('home.demo', ['post' => $posts[$id]]);
})->name('demo.show');

Route::get('/demos', function() use ($posts){
    return view('home.demo_index', compact('posts'));
})->name('demo.index');

```
Notice the use of compact , a very common function to transmit data to views  ! 


**demo_index.blade.php**
```
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
```

Of course i will cover implement soon models and controllers et we will use real data :) 

### Partial templates 
! Convention partials > _file_name.blade.php 
Beware that all variables from the file calling the template are available in the template itself which is not true when using the @each directive ! 

## Request and response 
### General information
```
Route::get('/response', function() use ($posts){
    return response($posts, 200)
           ->header('Content-Type', 'application/json')
           ->cookie('COOKIE_NAME', 'COOKIE_VALUE', 3600);
})->name('response');
```
Take on look on the browser console and see the result ! 

### Redirect
```
Route::get('/redirect', function(){
    return redirect('/contact');
});
```

Some other usefull feature : 
```
    return back(); 
    return redirect()->route('demo.show', ['id' => 1]);  //redirection to named route with args 
    return redirect()->away(''https://google.com); //redirection to another website ! 
```

### Returning JSON 
```
    Route::method('url', function() use($posts) {
        return response()->json($posts); //notice the use of the response helper again and again 
    })->name('route_name'); 
```

### Returning File download 
```
     Route::method('url', function() {
        return response()->download(public_path('/file_name.extension', 'alt_file_name', [optional_header]));
     })->name('route_name'); 
```

### Request Overview 
Try out this code inside your closure / controller 
```
    dd(request()->all()); //the request helper is available basically anywhere 
    dd(request()->input('name', fallback_value)); 
    dd(request()->query('name', fallback_value));  //will check out only for query parameters 
```

There are lots of usefull other request options
```
    dd(request()->only(['input1', 'input2']); 
    dd(request()->except(['input1', 'input2']); 
    dd(request()->has('input_name')); 
    request()->hasAny(['input1', 'input2']); 
    if(request()->filled('name'))

```

## Controller basics 
### Basics 
**create the controller**

php artisan make:controller HomeController 

**implement this very simple controller**
```
class HomeController extends Controller
{
    public function home()
    {
        return view('home.index');
    }

    public function contact()
    {
        return view('home.contact');
    }
}
```

**refactore the routing**
```
use App\Http\Controllers\HomeController;

Route::name('home.')->group(function(){
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
});
```

type php artisan route:list | grep home -> notice the controllername@method field !

### Single action controller 
php artisan make:controller NameController --invokable; 

This will create a controller which implements the __invoke method ! 

### Resoruce controller 
php artisan make:controller NameController -r which is the same as php artisan make:controller NameController --resource 

If you want to create a dependance injection with a model : php artisan make:controller -r --model=ModelName

And if you want to create a resource controller for an api php artisan make:controller NameController --api 

### Let's create a controller for our Posts ()
php artisan make:controller PostsController -r  (no model for now, notice the name which is a strong convention)

**Refactor routing :   web.php**
```
use App\Http\Controllers\PostsController;

Route::resource('posts', PostsController::class)->only(['index', 'show']); 
```
We will only use the index and show method for now since we don't have a dedicated model Yet 

**Refactor the view directory**
*views 
    *posts 
        *index.blade.php 
        *show.blade.php 

**Implement the PostsController**
```
class PostsController extends Controller
{
     private $posts = [
            1 => [
                'title' => 'title 1',
                'content' => 'content 1',
                'is_new' => true
            ],
            2 => [
                'title' => 'title 2',
                'content' => 'content 2',
                'is_new' => false
            ],
            3 => [
                'title' => 'title 3',
                'content' => 'content 3',
                'is_new' => false
            ]
    ];   
    
     /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->view('posts.index', ['posts' => $this->posts]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        abort_if(!isset($this->posts[$id]), 404);
        return response()->view('posts.show', ['post' => $this->posts[$id]]);
    }
}
```
In 2021 we care about php typing ! :) 



