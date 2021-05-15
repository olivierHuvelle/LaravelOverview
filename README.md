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

## Configuration and environments 
### Configuration and Environments overview 
Go to config directory (root > config)

Ex : database.php -> see how it uses env helper (which will check if the value is set in the .env file) and has in general a fall_back value 

Set the db connexion settings in your .env file (DB_DATABASE, DB_USERNAME, DB_PASSWORD)


## Database 

* Migration -> manage the database schema 
* Seeding -> populate the database with data (this can be fake data of course) 
* Raw Queries and Eloquent ORM 

### Migration overview   

migration is a php class , it has 2 methods 
* up() and down() 
* it uses Schema class (tables) and Blueprint class (table columns) 

artisan commands 
* artisan migrate 
* artisan migrate:rollback 

the migrations are in the migrations folder 

### Creating and running migrations 
**create the model and migration**

php artisan make:model Post -m //-m for --migration it will create the associated migration 

**implement the migration**
```
class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
```

**apply/rollback migration**
php artisan migrate 
php artisan migrate:rollback 

### Eloquent ORM 

1 model -> 1 table  
model properties match the column names 

model has instance-based methods and static-methods 
* instance-based methods 
    * save() 
    * delete() 
* static methods 
    * find()
    * create() // creates a model with data and immediately save it 
    * make() // creates a model with data without saving 
* additional features 
    * relationships 
    * events 

### Models + introducing tinker  

**launch tinker**
php artisan tinker 

**create and save a new model instance**
```
$post = new Post(); 
$post->title = 'a first title', 
$post->content = 'a first description'; 
$post->save(); //returns true if no error occurred 
$post; //returns the post with all its properties
```

**update the model instance**
```
$post = Post::find(1);  //if the id does not exist returns null 
$post->title = 'a new title'; 
$post->save(); 
```


### Retrieving a single model 
an alternative to ::find ::findOrFail :) 
```
$post = Post::findOrFail(1); 
```

### Retrieving multiple models / collection  
```
$posts = Post::all(); //returns a collection (which has a LOT of convenient methods) 
$posts = Post::find([1,2,...,n]); //returns a collection of the found elements 
$posts->first(); 
$posts->count(); 
$posts->map(function($post){ somde code }); 
```
<p><a href="https://laravel.com/docs/8.x/collections">See the collection documentation, it's great :D </a></p>

### Query builder 
```
php artisan tinker 

User::factory()->count(5)->create(); 
$users = User::where('id', '>=', 3)->get(); 
$users = User::where('id', '>=', 2)->orderBy('id', 'desc')->get(); 
$posts = Post::orderBy('created_at', 'desc')->take(5)->get(); // look how easy it is :D 
```

General principle : QueryBuilder method call returns a new Query builder instance so you'll always need to call the get method of the builder object 

### Practical 
**Refactore the PostsController**

```
public function index()
{
    return response()->view('posts.index', ['posts' => Post::all()]);
}

/**
 * Display the specified resource.
 *
 * @param Post $post
 * @return Response
 */
public function show(Post $post)
{
    return response()->view('posts.show', ['post' => $post]);
}
```

Notice we changed the show method by adding a dependance injection -> it works as a findOrFail($id) and throws a 404 error if resource not found 



## Assets and Styling 
Webpack made easy thx to mix ! 

Some features found in the mix 
* CSS : Less, Saxs, Stylus and postCSS support 
* Javascript : ES2017+ , extracting vendor libs, hot module replacement, automatic versionning, build and compule vue components, modules 
* General : Versioning, Cache-busting and minification 

### Install bootstrap (notice that laravel ui is not recommended anymore)
composer require laravel/ui ^3.0.0 

php artisan ui bootstrap 

npm install 

npm run dev (compiles the assets)

### Using NOM and compiling assets 
see package.json -> you will find all the required dependencies (i will let it as it is even though there are some old and deprecated dependencies such as jQuery : 
the main goal of this project is to focus on laravel capabilities and not on front code :)) 

npm run XXX -> commands found in the package.json script object i.e npm run dev  or npm run hot 

Beware that you can actually have some troubles dued to webpack-cli conflicts (local and global versions killing each other) 

### Include assets in views 
**app.blade.php**
```
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>LaravelOverview | @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
```

Notice the defer attribute in the script tag ! If you don't include it the browser will wait until it has completely loaded the script file before rendering the view 

### Versioned assets (cache improvments) 

**webpack.mix.js**
```
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()

if(mix.inProduction())
{
    mix.inProduction()
}
```

**app.blade.php**
```
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>LaravelOverview | @yield('title')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        <main>
            @yield('content')
        </main>
    </body>
</html>
```

## Forms 
Important note : every route which is not rendered by the get method is protected by the VerifyCsrfToken to prevent csrf attacks. 
**Never use a route with get method if this one is associated with any kind of data-manipulation**

## A quick refactore 
I will implement the CRUD actions quickly 

### Routing refactoring 
```
Route::resource('posts', PostsController::class)->except(['destroy']);
```
I don't allow the destroy method for now as i'm not about to implement this method yet 

### Model refactoring 
**Post.php**
```
class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
```
I simply add the id field to guarded properties, it will allow me to implement mass assignment easily 
When working with mass assignement you have 2 options : first listing all the fillable properties , second list the guarded properties 

```
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content']; // it's longer and we will implement other properties later that's why i chose to work with guarded instead 
}
```

### PostController 
**create method**
```
/**
 * Show the form for creating a new resource.
 *
 * @return Response
 */
public function create()
{
    return response()->view('posts.create', ['post' => new Post()]);
}
```
I've chosen to create a post in the create method and then transmit it to the view, you could absolutely not doing so and transmit nothing at all but it's quiet cumbersome to manage optional properties in the views
 
### Views + Controller refactoring 
**new folder structure**
* posts 
    * show
    * index 
    * edit 
    * crete 
    * partials 
        * _form 

I chose this folder structure as i will use the same form for edit and create the post 

**create.blade.php**
```
<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title', 'create a post')

@section('content')
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf()
        @include('posts.partials._form')
    </form>
@endsection
```

The first line is here only for my IDE (phpstorm) to propose me a better auto-completion 

Notice de @csrf() directive 

**posts.partials._form.blade.php**
```
<?php /** @var \App\Models\Post $post  */ ?>

<div class="form-group my-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('name', $post->title) }}">
</div>

<div class="form-group my-3">
    <label for="content">Content</label>
    <textarea name="content" id="content" class="form-control" rows="5">{{ old('description', $post->content) }}</textarea>
</div>

<button type="submit" class="btn btn-primary my-3">Create the post</button>
```

I will refactore this soon with blade components =) 

**PostRequest**
```
php artisan make:request PostRequest

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'min:5']
        ];
    }
}
```
The authorize method can be changed fatherly (see policies and dates) for now i will let this request accessible 

ALWAYS sanitize data, NEVER TRUST user input 

**PostController**
```
/**
 * Store a newly created resource in storage.
 *
 * @param PostRequest $request
 * @return Response
 */
public function store(PostRequest $request)
{
    $data = $request->validated();

    $post = new Post();
    $post->fill(Arr::except($data, $post->getGuarded()));
    $post->save();
    
    return redirect()->route('posts.show', ['post' => $post]);
}
```
Some notes 
* Use the request (data validation) 
* The use of the Arr helper which has a tone of useful features and shortcuts 
* guetGuarded (if we had worked with fillable) : $post->fill(Arr:only($data, $post->getFillable())

If you don't want to use mass assignment 
```
/**
 * Store a newly created resource in storage.
 *
 * @param PostRequest $request
 * @return Response
 */
public function store(PostRequest $request)
{
    $data = $request->validated();

    $post = new Post();
    $post->title = $data['title']; 
    $post->content = $data['content']; 
    // imagine the nightmare if you have to manage 50 fields ... 
    $post->save();
    
    return redirect()->route('posts.show', ['post' => $post]);
}
```

**posts.partials._form.blade.php**
Ok but i want to show the validation error messages ! No problem the SareErrorsFromSession middleware will save us :-) 


```
@if($errors->any())
<div class="alert alert-danger" role="alert">
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

previous code 
```

Ok but i would rather like the specific-field error to be connected and use the dedicated invalid-feedback of bootstrap ! No problemo .... 

```
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
```

Notice that waiting for back validation to have a feedback ... is not very friendly regarding to user experience , you can of course do front-end validation :) 

### Adding a flash session message 
It would be great to display a flash message such as 'the post was successfully created'

**PostController**
```
/**
 * Store a newly created resource in storage.
 *
 * @param PostRequest $request
 * @return Response
 */
public function store(PostRequest $request)
{
    $data = $request->validated();

    $post = new Post();
    $post->fill(Arr::except($data, $post->getGuarded()));
    $post->save();

    $request->session()->flash('success', 'The blog post was successfully created');

    return redirect()->route('posts.show', ['post' => $post]);
}
```
**app.blade.php**
```
@if(session('success'))
    @include('layouts.partials._flash_alert')
@endif
<main>
    @yield('content')
</main>
```

**layouts.partials._flash_alert.blade.php**
```
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>{{ session('success') }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
```

## CRUD 
### Edit 
**PostController**
```
/**
 * Show the form for editing the specified resource.
 *
 * @param Post $post
 * @return Response
 */
public function edit(Post $post)
{
    return response()->view('posts.edit', ['post' => $post]);
} 
```
**posts.edit.blade.php**
```
<?php /** @var \App\Models\Post $post  */ ?>

@extends('layouts.app')

@section('title', 'update the post')

@section('content')
    <form action="{{ route('posts.update', ['post' => $post]) }}" method="POST">
        @csrf()
        @method('PUT')
        @include('posts.partials._form')
    </form>
@endsection
```

Nothing really important to show here BUT the @method directive, as you know when you want to update a resource you can either use PUT or PATCH methods 

### Update 
**PostController**
```
/**
 * Update the specified resource in storage.
 *
 * @param PostRequest $request
 * @param Post $post
 * @return RedirectResponse
 */
public function update(PostRequest $request, Post $post)
{
    $data = $request->validated();

    $post->fill(Arr::except($data, $post->getGuarded()));
    $post->save();

    $request->session()->flash('success', 'The blog post was successfully updated');

    return redirect()->route('posts.show', ['post' => $post]);
}
```

Nothing new to say , juste notice de injection dependance 

If you want to do it old school 
```
public function update(PostRequest $request, int $id)
{
    $data = $request->validated();
    
    $post = Post::findOrFail($id); 
    $post->fill(Arr::except($data, $post->getGuarded()));
    $post->save();

    $request->session()->flash('success', 'The blog post was successfully updated');

    return redirect()->route('posts.show', ['post' => $post]);
}
```

### Destroy 
**web.php**
```
Route::resource('posts', PostsController::class);
```
We dont need to except the destroy method anymore :-) 

**posts.show**
```
... previous content 
<form action="{{ route('posts.destroy', ['post' => $post]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger my-3">Delete the post</button>
</form>
```

**PostController**
```
/**
 * Remove the specified resource from storage.
 *
 * @param Request $request
 * @param Post $post
 * @return RedirectResponse
 */
public function destroy(Request $request, Post $post)
{
    $post->delete();

    $request->session()->flash('success', 'The blog post was successfully deleted');

    return redirect()->route('posts.index');
}
```

Instead of adding $request in the controller we could implement it using the session helper
```
public function destroy(Post $post)
{
    $post->delete();
    
    session()->flash('success', 'The blog post was successfully deleted');

    return redirect()->route('posts.index');
}
```

## Styling (a bit) the app 
No comment since it's not a bootstrap tutorial :p 
### New folder structure 
* home 
    * contact 
    * index 
* layouts
    * partials 
        * _flash_alert
        * _navigation
    * app
* posts 
    * partials 
        * _form 
    * create 
    * edit 
    * index 
    * show 

**app.blade.php**
```
<body>
    @include('layouts.partials._navigation')
    <main class="container">
        @if(session('success'))
            @include('layouts.partials._flash_alert')
        @endif
        @yield('content')
    </main>
</body>
```
**_navigation.blade.php**
```
<div class="d-flex flex-column flex-md-row align-items-center px-md-5 py-2 mb-3 border-bottom shadow-sm bg-white">
    <h2 class="my-0 mr-md-auto">Laravel Overview</h2>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="text-dark p-2" href="{{ route('home.index') }}">Home</a>
        <a class="text-dark p-2" href="{{ route('home.contact') }}">Contact</a>
        <a class="text-dark p-2" href="{{ route('posts.index') }}">Posts</a>
        <a class="text-dark p-2" href="{{ route('posts.create') }}">Create a Post</a>
    </nav>
</div>
```
**posts.index.blade.php**
```
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
```

**posts.show.blade.php**
```
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
```

## Testing 
TDD is a must for every complex app you will add a ton of new functionalities and have to be sure that the code added doesn't break your application 

Notice that laravel comes with a very handy tool : Dusk which allows to perform end-to-end tests easily (but that's not what i will use here). We will stick to functional tests though 

### Introduction

Types of tests 
* unit tests 
* feature tests ==> i will use this :) 
* end to end tests 

root > 
 * tests 
    * Feature > tests (feature tests)
    * Unit > tests (unit tests)
    * TestCase.php (all the tests will extend from this)

**run the tests**
./vendor/bin/phpunit to run the test or more intuitive php artisan test 

Test function anatomy 
* arrange (optional) 
* act (mandatory)
* assert (mandatory)

### Testing configuration and environment 
**phpunit.xml**

```
<php>
    <server name="APP_ENV" value="testing"/>
    <server name="BCRYPT_ROUNDS" value="4"/>
    <server name="CACHE_DRIVER" value="array"/>
    <!-- <server name="DB_CONNECTION" value="sqlite"/> -->
    <!-- <server name="DB_DATABASE" value=":memory:"/> -->
    <server name="MAIL_MAILER" value="array"/>
    <server name="QUEUE_CONNECTION" value="sync"/>
    <server name="SESSION_DRIVER" value="array"/>
    <server name="TELESCOPE_ENABLED" value="false"/>
    <server name="DB_CONNECTION" value="sqlite_testing"/>
</php>
```

**database.php**
```
 'connections' => 
    [
        'sqlite_testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],
        ... 
```

**clear the cache**
php artisan config:clear 

### Writing functional test 

Testing the static pages (home and contact) -> homeController 

php artisan make:test HomeTest 

**HomeTest.php**
```
    public function test_homePage_success()
    {
        $response = $this->get(route('home.index'));
        $response->assertStatus(200);
        $response->assertSeeText('Homepage');
    }

    public function test_contactPage_success()
    {
        $response = $this->get(route('home.contact'));
        $response->assertStatus(200);
        $response->assertSeeText('Contact page');
    }
```

### Testing database interaction 

php artisan make:test PostsTest 

A key factor to understand is that every test has to be independent from the others 

You might expose a connection error because your project doesn't know about sqlite 
```
sudo apt-get install php-sqlite3
composer update
composer require doctrine/dbal
```

Some SQLite constraints 
* if not nullable has to have a default value 
* 

**PostsTest.php**
Note : https://laravel.com/docs/8.x/http-tests#available-assertions 
```
class PostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_post_success()
    {
        $response = $this->get(route('posts.index'));
        $response->assertSeeText('No post yet!');
        $response->assertStatus(200);
    }

    public function test_1post_success()
    {
        $post = Post::create([
            'title' => 'this is a valid title',
            'content' => 'this is a valid content we will refactore this using factories'
        ]);

        $this->assertDatabaseHas('posts', Arr::except($post->toArray(), ['id', 'created_at', 'updated_at']));

        $response = $this->get(route('posts.show', ['post' => $post]));
        $response->assertSeeText( $post->title );
        $response->assertSeeText( $post->content );
        $response->assertStatus(200);
    }
}
```


### Testing CRUD and 
