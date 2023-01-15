<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(1);
        return view('admin.posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(StorePost $request)
    {
        Post::create($request->all());
        return redirect()->route('posts.index');
    }

    public function show($id)
    {
        if (!$post = Post::where('id', $id)->first())
            return redirect()->route('posts.index');

        return view('admin.posts.show', ['post' => $post]);
    }

    public function edit($id)
    {
        if (!$post = Post::find($id))
            return redirect()->back();

        return view('admin.posts.edit', ['post' => $post]);
    }

    public function update(UpdatePost $request, $id)
    {
        if (!$post = Post::find($id))
            return redirect()->back();

        $data = $request->all();
        $post->update($data);

        return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        if (!$post = Post::find($id))
            return redirect()->route('posts.index');

        $post->delete();
        return redirect()->route('posts.index')
            ->with('message', 'Post deletado com sucesso');
    }

    public function search(Request $request)
    {
        $filters = $request->except('_token');

        $posts = Post::where('title', 'like', "%{$request->search}%")
            ->orWhere('content', 'like', "%{$request->search}%")
            ->paginate(1);
        return view('admin.posts.index', ['posts' => $posts, 'filters' => $filters]);
    }
}
