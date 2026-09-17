@extends('layouts.app')

@section('title')
    Inicio
@endsection

@section('contenido')
    <section class="container mx-auto px-4 py-8 sm:px-6">
        <div class="mb-10 flex flex-col items-center justify-center gap-2 text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-sky-100 px-3 py-1 text-xs font-bold text-sky-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
                Actividad reciente
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Lo último de la comunidad
            </h1>
            <div class="mt-1 h-1 w-16 rounded-full bg-linear-to-r from-sky-500 to-orange-500"></div>
        </div>

        @if ($posts->count())
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($posts as $post)
                    @include('posts.card', [
                        'post' => $post,
                        'activityLabel' => $post->activity_label,
                        'activityAt' => $post->activity_at,
                    ])
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-gray-200 bg-white p-12 text-center shadow-sm">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-50 text-sky-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Aún no hay actividad</h3>
                <p class="mt-1 max-w-sm text-sm text-gray-500">Cuando la comunidad publique, comente o dé me gusta, lo verás aquí.</p>
            </div>
        @endif
    </section>
@endsection
