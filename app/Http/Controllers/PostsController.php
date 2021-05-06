<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
