@extends('layouts.app')

@section('title')
    {{ $post->title }}
@endsection

@section('contenido')
    <div class="container mx-auto max-w-6xl px-4 py-6 md:py-8">
        <!-- Navegación de regreso -->
        <div class="mb-6">
            <a
                href="{{ route('post.index', $post->user->username) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-600 shadow-sm transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-600"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Volver al perfil de {{ $post->user->username }}
            </a>
        </div>

        <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
            <!-- Columna Izquierda: Detalle de la Publicación -->
            <div class="space-y-6 lg:col-span-7">
                <article class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl shadow-sky-500/5">
                    <!-- Cabecera del Autor -->
                    <div class="flex items-center justify-between border-b border-gray-100 p-5 sm:px-6">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('post.index', $post->user->username) }}" class="group relative">
                                <div class="absolute -inset-0.5 rounded-full bg-linear-to-r from-sky-500 to-orange-500 opacity-75 blur transition duration-300 group-hover:opacity-100"></div>
                                <div class="relative h-11 w-11 overflow-hidden rounded-full border-2 border-white bg-gray-50">
                                    <img
                                        src="{{ $post->user->profile_image ? asset('profiles/'.$post->user->profile_image) : asset('usuario.svg') }}"
                                        alt="Avatar de {{ $post->user->username }}"
                                        class="h-full w-full object-cover"
                                        data-user-avatar
                                    />
                                </div>
                            </a>
                            <div>
                                <a href="{{ route('post.index', $post->user->username) }}" class="block text-base font-bold text-gray-900 transition hover:text-sky-600">
                                    {{ $post->user->username }}
                                </a>
                                <p class="flex items-center gap-1 text-xs text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $post->created_at ? $post->created_at->diffForHumans() : '' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                        @if ($post->github_url)
                            <a
                                href="{{ $post->github_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-bold text-white shadow-md transition hover:bg-gray-800 hover:shadow-lg"
                                title="Ver repositorio en GitHub"
                            >
                                <svg class="size-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                                </svg>
                                <span class="hidden sm:inline">GitHub</span>
                            </a>
                        @endif

                        @if ($post->site_url)
                            <a
                                href="{{ $post->site_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-bold text-white shadow-md transition hover:bg-gray-800 hover:shadow-lg"
                                title="Ver sitio web"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18" />
                                </svg>
                                <span class="hidden sm:inline">Sitio</span>
                            </a>
                        @endif
                        </div>
                    </div>

                    <!-- Contenedor de la Imagen -->
                    <div class="relative flex items-center justify-center overflow-hidden bg-gray-950">
                        <img
                            src="{{ asset('uploads') . '/' . $post->image }}"
                            alt="Imagen del post: {{ $post->title }}"
                            class="max-h-125 w-full object-contain transition-transform duration-500 hover:scale-105"
                        />
                    </div>

                    <!-- Barra de interacción y detalles -->
                    <div class="space-y-4 p-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <!-- Me gusta counter/button -->
                            <div class="flex items-center gap-3" data-like-widget>
                                @auth
                                    <form action="{{ route('posts.likes.destroy', ['post' => $post]) }}" method="POST" class="js-like-form {{ $post->checkLike(auth()->id()) ? '' : 'hidden' }}" data-variant="liked">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-full bg-orange-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-sm transition-all hover:scale-105 hover:bg-orange-600 focus:outline-none active:scale-95"
                                            title="Quitar me gusta"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.575-4.683-4.575-1.761 0-3.315.932-4.14 2.308-.825-1.376-2.379-2.308-4.14-2.308-2.584 0-4.683 2.09-4.683 4.575 0 4.148 7.375 9.775 8.423 10.558a.75.75 0 00.836 0C13.625 18.025 21 12.398 21 8.25z" />
                                            </svg>
                                            <span class="hidden sm:inline" data-like-count>{{ $post->likes()->count() }} Likes</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('posts.likes.store', ['post' => $post]) }}" method="POST" class="js-like-form {{ $post->checkLike(auth()->id()) ? 'hidden' : '' }}" data-variant="unliked">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-full bg-orange-50 px-3.5 py-1.5 text-xs font-bold text-orange-600 shadow-sm transition-all hover:scale-105 hover:bg-orange-500 hover:text-white focus:outline-none active:scale-95"
                                            title="Me gusta"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.575-4.683-4.575-1.761 0-3.315.932-4.14 2.308-.825-1.376-2.379-2.308-4.14-2.308-2.584 0-4.683 2.09-4.683 4.575 0 4.148 7.375 9.775 8.423 10.558a.75.75 0 00.836 0C13.625 18.025 21 12.398 21 8.25z" />
                                            </svg>
                                            <span class="hidden sm:inline" data-like-count>{{ $post->likes()->count() }} Likes</span>
                                        </button>
                                    </form>
                                @endauth

                                <button
                                    type="button"
                                    onclick="if (navigator.share) { navigator.share({ title: @js($post->title), url: @js(route('posts.show', ['user' => $user, 'post' => $post])) }); } else { navigator.clipboard.writeText(@js(route('posts.show', ['user' => $user, 'post' => $post]))).then(() => alert('¡Enlace copiado al portapapeles!')); }"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3.5 py-1.5 text-xs font-bold text-sky-600 shadow-sm transition-all hover:scale-105 hover:bg-sky-600 hover:text-white focus:outline-none active:scale-95"
                                    title="Compartir"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0-10.628a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zm0 10.628a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                                    </svg>
                                    <span class="hidden sm:inline">Compartir</span>
                                </button>

                                @if (auth()->id() === (int) $post->user_id)
                                    <a
                                        href="{{ route('posts.edit', $post) }}"
                                        class="inline-flex items-center gap-1.5 rounded-full bg-sky-50 px-3.5 py-1.5 text-xs font-bold text-sky-600 shadow-sm transition-all hover:scale-105 hover:bg-sky-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/25 active:scale-95"
                                        title="Editar publicación"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                                        </svg>
                                        <span class="hidden sm:inline">Editar</span>
                                    </a>

                                    <form
                                        action="{{ route('posts.destroy', $post) }}"
                                        method="POST"
                                        data-delete-form
                                        data-delete-title="¿Eliminar publicación?"
                                        data-delete-description="Esta acción eliminará la publicación y sus comentarios. No se puede deshacer."
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3.5 py-1.5 text-xs font-bold text-red-600 shadow-sm transition-all hover:scale-105 hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500/25 active:scale-95"
                                            title="Eliminar publicación"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10.5 0v10.125A2.375 2.375 0 009.875 20h4.25a2.375 2.375 0 002.375-2.375V7.5m-7.5 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7.5m-5.25 3.75v4.5m4.5-4.5v4.5" />
                                            </svg>
                                            <span class="hidden sm:inline">Eliminar</span>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <button
                                type="button"
                                id="post-details-toggle"
                                class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-bold text-gray-600 transition hover:bg-gray-200"
                                aria-haspopup="dialog"
                                aria-controls="post-details-modal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                Detalles
                            </button>
                        </div>

                        <!-- Título y Descripción -->
                        <div>
                            <h1 class="mb-3 text-2xl font-black tracking-tight text-gray-900 sm:text-3xl">
                                {{ $post->title }}
                            </h1>
                            <p class="whitespace-pre-line rounded-2xl border border-gray-100 bg-gray-50/50 p-4 text-sm leading-relaxed text-gray-600 md:text-base">
                                {{ $post->description }}
                            </p>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Columna Derecha: Formulario y Lista de Comentarios -->
            <div class="space-y-6 lg:col-span-5">
                @auth
                    <!-- Tarjeta para Agregar Comentario (Solo usuarios autenticados) -->
                    <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-xl shadow-sky-500/5">
                        <div class="mb-5 flex items-center gap-3 border-b border-gray-100 pb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-50 text-sky-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.39 0-4.74.184-7.043.513C3.377 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-extrabold text-gray-900">Agrega un comentario</h2>
                                <p class="text-xs text-gray-500">Comparte tu opinión con la comunidad</p>
                            </div>
                        </div>

                        @if (session('mensaje'))
                            <div id="mensaje-flash" class="group relative mb-5 flex items-center gap-3 overflow-hidden rounded-2xl border border-emerald-200 bg-linear-to-r from-emerald-500 via-teal-500 to-sky-500 p-4 text-white shadow-lg shadow-emerald-500/20 transition-all duration-700 ease-in-out">
                                <div class="absolute inset-0 -translate-x-full bg-linear-to-r from-transparent via-white/20 to-transparent animate-[shimmer_2s_ease-in-out_infinite]"></div>
                                <div class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                                <p class="relative text-sm font-bold tracking-wide">
                                    {{ session('mensaje') }}
                                </p>
                            </div>

                            <script>
                                setTimeout(() => {
                                    const mensajeFlash = document.getElementById('mensaje-flash');
                                    if (!mensajeFlash) return;
                                    mensajeFlash.style.opacity = '0';
                                    mensajeFlash.style.transform = 'translateY(-0.5rem)';
                                    mensajeFlash.addEventListener('transitionend', () => mensajeFlash.remove(), { once: true });
                                }, 5000);
                            </script>
                        @endif

                        <form action="{{ route('comments.store', ['post' => $post, 'user' => $user]) }}" method="POST" class="js-comment-form space-y-4">
                            @csrf
                            <div>
                                <label for="comment" class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    Comentario
                                </label>
                                <textarea
                                    id="comment"
                                    name="comment"
                                    placeholder="Escribe lo que piensas sobre esta publicación..."
                                    rows="4"
                                    class="w-full resize-y rounded-2xl border bg-gray-50/70 p-4 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('comment') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                                ></textarea>
                                @error('comment')
                                    <p class="mt-2 flex items-center gap-1 text-xs font-bold text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3.5 text-sm font-bold uppercase tracking-wide text-white shadow-md shadow-sky-600/20 transition duration-300 hover:bg-sky-700 hover:shadow-lg hover:shadow-sky-600/30 focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                Comentar
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Mensaje para Invitados -->
                    <div class="rounded-3xl border border-sky-100 bg-sky-50/60 p-6 text-center shadow-sm">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-sky-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">¿Quieres unirte a la conversación?</h3>
                        <p class="mt-1 text-xs text-gray-600">Inicia sesión o regístrate para poder dar me gusta y comentar publicaciones.</p>
                        <div class="mt-4 flex items-center justify-center gap-3">
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-sky-600/20 transition hover:bg-sky-700">
                                Iniciar sesión
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                Registrarse
                            </a>
                        </div>
                    </div>
                @endauth

                <!-- Lista de Comentarios (Con Scrollbar) -->
                <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-xl shadow-sky-500/5">
                    <div class="mb-4 flex items-center justify-between border-b border-gray-100 pb-4">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5 text-orange-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-.623 0-1.243-.02-1.86-.059A44.75 44.75 0 0112 17.25c-1.636 0-3.245-.098-4.814-.286A2.25 2.25 0 015.25 14.89V10.6c0-.97.616-1.813 1.5-2.097M12 3c-4.97 0-9 3.186-9 7.12 0 2.2 1.272 4.168 3.25 5.434V18.75l2.842-1.895C10.093 17.07 11.037 17.12 12 17.12c4.97 0 9-3.186 9-7.12C21 6.186 16.97 3 12 3z" />
                            </svg>
                            <h3 class="text-base font-extrabold text-gray-900">Comentarios</h3>
                        </div>
                        <span class="rounded-full bg-orange-50 px-2.5 py-0.5 text-xs font-bold text-orange-600" id="comments-count">
                            {{ $post->commentsCount() }}
                        </span>
                    </div>

                    <!-- Contenedor scrollable -->
                    <div
                        id="comments-list"
                        class="max-h-100 overflow-y-auto pr-1.5 space-y-4 scrollbar-thin scrollbar-thumb-sky-200 scrollbar-track-transparent"
                        data-last-id="{{ $lastCommentId }}"
                        data-poll-url="{{ route('comments.latest', ['user' => $user, 'post' => $post]) }}"
                    >
                        @forelse ($comments as $comment)
                            <x-comment :comment="$comment" :post="$post" :user="$user" />
                        @empty
                            <!-- Estado Vacío -->
                            <div id="comments-empty-state" class="flex flex-col items-center justify-center border border-dashed border-gray-200 bg-gray-50/60 py-8 text-center rounded-2xl">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-100/70 text-orange-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a.75.75 0 01-1.074-.748 5.434 5.434 0 011.026-2.531C4.195 16.273 3 14.252 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800">No hay comentarios aún</p>
                                <p class="mt-1 max-w-50 text-xs text-gray-500">Sé el primero en expresar tu opinión sobre este post.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de Detalles de la Publicación -->
    <div id="post-details-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="post-details-modal-title">
        <div class="max-h-[85vh] w-full max-w-md overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 id="post-details-modal-title" class="text-lg font-extrabold text-gray-900">Detalles de la publicación</h3>
                <button type="button" id="post-details-close" class="rounded-full p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <dl class="mb-5 grid grid-cols-2 gap-3 text-center">
                <div class="rounded-xl bg-gray-50 p-3">
                    <dt class="text-[11px] font-medium text-gray-500">Me gusta</dt>
                    <dd class="text-lg font-black text-orange-500">{{ $post->likes()->count() }}</dd>
                </div>
                <div class="rounded-xl bg-gray-50 p-3">
                    <dt class="text-[11px] font-medium text-gray-500">Comentarios</dt>
                    <dd class="text-lg font-black text-sky-600">{{ $post->commentsCount() }}</dd>
                </div>
                <div class="col-span-2 rounded-xl bg-gray-50 p-3 text-left">
                    <dt class="text-[11px] font-medium text-gray-500">Publicado</dt>
                    <dd class="text-sm font-bold text-gray-800">{{ $post->created_at?->format('d/m/Y H:i') }}</dd>
                </div>
                @if ($post->github_url)
                    <div class="col-span-2 rounded-xl bg-gray-50 p-3 text-left">
                        <dt class="text-[11px] font-medium text-gray-500">Repositorio</dt>
                        <dd class="truncate text-sm font-bold text-sky-600">
                            <a href="{{ $post->github_url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $post->github_url }}</a>
                        </dd>
                    </div>
                @endif
            </dl>

            <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-700">Les gusta a</h4>
            <ul class="space-y-3">
                @forelse ($likedBy as $like)
                    <li class="flex items-center gap-3">
                        <div class="h-9 w-9 shrink-0 overflow-hidden rounded-full border border-gray-100 bg-gray-50">
                            <img
                                src="{{ $like->user?->profile_image ? asset('profiles/'.$like->user->profile_image) : asset('usuario.svg') }}"
                                alt="Avatar de {{ $like->user?->username }}"
                                class="h-full w-full object-cover"
                            />
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ $like->user?->username ?? 'Usuario eliminado' }}</span>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Aún nadie ha dado me gusta a esta publicación.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
