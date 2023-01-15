@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

@csrf
<label for="title">Título</label>
<input type="text" name="title" id="title" value="{{ $post->title ?? old('title') }}" placeholder="Título">
<label for="content">Conteúdo</label>
<textarea name="content" id="content" cols="30" rows="4" placeholder="Conteúdo">{{ $post->content ?? old('content') }}</textarea>
<button type="submit">Enviar</button>
