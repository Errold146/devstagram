@extends('layouts.app')

@section('title')
    Editar publicación
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endpush

@section('contenido')
    <div class="mx-auto grid max-w-5xl items-stretch gap-6 lg:grid-cols-2 lg:gap-8">
        <div class="group relative min-h-112 overflow-hidden rounded-2xl shadow-xl shadow-sky-950/10">
            <form
                action="{{ route('image.store') }}"
                method="POST"
                enctype="multipart/form-data"
                id="dropzone"
                class="dropzone h-full w-full rounded-2xl border-2 border-dashed border-gray-400"
            >
                @csrf
            </form>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
            <div class="mb-8">
                <p class="mb-2 text-sm font-bold uppercase tracking-[0.18em] text-orange-500">Editar post</p>
                <h3 class="text-2xl font-black text-gray-900">Actualiza tu publicación</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">Modifica los datos o reemplaza la imagen cuando lo necesites.</p>
            </div>

            <form action="{{ route('posts.update', $post) }}" method="POST" class="space-y-5" novalidate>
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="mb-2 block text-sm font-bold text-gray-700">Título</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Título del post"
                        autocomplete="title"
                        required
                        class="w-full rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('title') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                        value="{{ old('title', $post->title) }}"
                    />
                    @error('title')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-bold text-gray-700">Descripción</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Descripción del post"
                        autocomplete="description"
                        required
                        rows="6"
                        class="w-full resize-y rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                    >{{ old('description', $post->description) }}</textarea>
                    @error('description')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="github_url" class="mb-2 block text-sm font-bold text-gray-700">URL de GitHub</label>
                    <input
                        type="url"
                        id="github_url"
                        name="github_url"
                        placeholder="https://github.com/usuario/repositorio"
                        autocomplete="url"
                        required
                        class="w-full rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('github_url') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                        value="{{ old('github_url', $post->github_url) }}"
                    />
                    @error('github_url')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_url" class="mb-2 block text-sm font-bold text-gray-700">URL del Sitio</label>
                    <input
                        type="url"
                        id="site_url"
                        name="site_url"
                        placeholder="https://mi-sitio.com"
                        autocomplete="url"
                        class="w-full rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('site_url') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                        value="{{ old('site_url', $post->site_url) }}"
                    />
                    @error('site_url')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input name="image" type="hidden" value="{{ old('image', $post->image) }}" />
                    @error('image')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a
                        href="{{ route('posts.show', ['user' => $post->user, 'post' => $post]) }}"
                        class="inline-flex w-full items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-3.5 text-sm font-bold text-gray-600 shadow-sm transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-500/10"
                    >
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-lg shadow-sky-600/20 transition duration-300 hover:bg-sky-700 hover:shadow-sky-600/35 focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
