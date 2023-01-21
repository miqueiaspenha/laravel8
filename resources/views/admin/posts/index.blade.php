@extends('admin.layouts.app')

@section('title', 'Listagem dos posts')

@section('content')
    <a href="{{ route('posts.create') }}">Criar Novo Post</a>
    <hr>
    @if (session('message'))
        {{ session('message') }}
    @endif

    <form action="{{ route('posts.search') }}" method="POST">
        @csrf
        <input type="text" name="search" placeholder="Filtrar:">
        <button type="submit">Filtrar</button>
    </form>

    <h1>Posts</h1>
    @foreach ($posts as $post)
        <p>
            <img src="{{ url('storage/' . $post->image) }}" alt="{{ $post->title }}" style="max-width: 100px">
            {{ $post->title }}
            [ <a href="{{ route('posts.show', ['id' => $post->id]) }}">Ver</a> |
            <a href="{{ route('posts.edit', ['id' => $post->id]) }}">Editar</a> ]
        </p>
    @endforeach

    @if (isset($filters))
        {{ $posts->appends($filters)->links() }}
    @else
        {{ $posts->links() }}
    @endif
@endsection
