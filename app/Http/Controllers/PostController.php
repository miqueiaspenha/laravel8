<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Http\Requests\UpdatePost;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $data = $request->all();

        if ($request->image->isValid()) {
            $fileName = Str::of($request->title)->slug('-') . '.' . $request->image->getClientOriginalExtension();
            $image = $request->image->storeAs('posts', $fileName);
            $data['image'] = $image;
        }

        Post::create($data);
        return redirect()
            ->route('posts.index')
            ->with('message', 'Post criado com sucesso');
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

        if ($request->image && $request->image->isValid()) {
            if (Storage::exists($post->image))
                Storage::delete($post->image);

            $fileName = Str::of($request->title)->slug('-') . '.' . $request->image->getClientOriginalExtension();
            $image = $request->image->storeAs('posts', $fileName);
            $data['image'] = $image;
        }

        $post->update($data);

        return redirect()
            ->route('posts.index')
            ->with('message', 'Post atualizado com sucesso');
    }

    public function destroy($id)
    {
        if (!$post = Post::find($id))
            return redirect()->route('posts.index');

        if (Storage::exists($post->image))
            Storage::delete($post->image);
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
