@extends('layouts.app')

@section('title')
    Crear Nuevo Post
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
                class="dropzone border-dashed border-2 border-gray-400 w-full h-full rounded-2xl flex flex-col justify-center items-center"
            >
                @csrf
            </form>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
            <div class="mb-8">
                <p class="mb-2 text-sm font-bold uppercase tracking-[0.18em] text-sky-600">Nuevo post</p>
                <h3 class="text-2xl font-black text-gray-900">Cuéntale a la comunidad</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">Añade un título claro y una descripción que explique tu publicación.</p>
            </div>

            <form action="{{ route('posts.store') }}" method="POST" class="space-y-5" novalidate>
                @csrf
                <div>
                    <label for="title" class="mb-2 block text-sm font-bold text-gray-700">Titulo</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="Titulo del Post"
                        autocomplete="title"
                        required
                        class="w-full rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('title') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                        value="{{ old('title') }}"
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
                        placeholder="Descripción del Post"
                        autocomplete="description"
                        required
                        rows="6"
                        class="w-full resize-y rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('description') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                    >{{ old('description') }}</textarea>
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
                        value="{{ old('github_url') }}"
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
                        value="{{ old('site_url') }}"
                    />
                    @error('site_url')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        name="image"
                        type="hidden"
                        value="{{ old('image') }}"
                    />
                    @error('image')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <input
                    type="submit"
                    value="Crear Post"
                    class="mt-3 w-full cursor-pointer rounded-xl bg-sky-600 px-4 py-3.5 font-bold uppercase tracking-wide text-white shadow-lg shadow-sky-600/20 transition duration-300 hover:bg-sky-700 hover:shadow-sky-600/35 focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                />
            </form>
        </div>
    </div>
@endsection
