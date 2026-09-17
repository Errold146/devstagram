<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @stack('styles')
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>DevStagram - @yield('title')</title>
    </head>
    <body class="flex min-h-screen flex-col bg-gray-50 text-gray-900 antialiased">
        <header class="border-b border-gray-200 bg-white/95 shadow-sm backdrop-blur">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 py-4 sm:px-8">
                <a href="/" class="flex items-center gap-3" aria-label="Ir a la página principal de DevStagram">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-600 text-lg font-black text-white shadow-lg shadow-sky-600/20">D</span>
                    <span>
                        <span class="block text-xl font-black tracking-tight text-gray-950">DevStagram</span>
                        <span class="hidden text-xs font-semibold uppercase tracking-[0.18em] text-gray-400 sm:block">Comunidad dev</span>
                    </span>
                </a>

                <div id="user-search" class="relative mx-4 hidden max-w-xs flex-1 md:block" data-search-url="{{ route('users.search') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input
                        type="search"
                        id="user-search-input"
                        autocomplete="off"
                        placeholder="Buscar usuario..."
                        class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-sm font-medium text-gray-700 outline-none transition focus:border-sky-300 focus:bg-white focus:ring-4 focus:ring-sky-500/15"
                    />
                    <ul
                        id="user-search-results"
                        class="absolute left-0 right-0 top-full z-40 mt-2 hidden max-h-80 overflow-y-auto rounded-xl border border-gray-100 bg-white py-1.5 shadow-xl shadow-sky-500/10"
                    ></ul>
                </div>

                @auth
                    <nav class="flex items-center gap-2 sm:gap-3" aria-label="Navegación principal">
                        <a
                            href="{{ route('post.index', auth()->user()->username) }}"
                            class="hidden items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-700 transition-colors hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-500/15 sm:flex"
                        >
                            <span class="flex h-6 w-6 items-center justify-center overflow-hidden rounded-md bg-sky-100 text-xs font-black text-sky-700">
                                <img
                                    src="{{ auth()->user()->profile_image ? asset('profiles/'.auth()->user()->profile_image) : asset('usuario.svg') }}"
                                    alt="Avatar de {{ auth()->user()->username }}"
                                    class="h-full w-full object-cover"
                                    data-user-avatar
                                />
                            </span>
                            <span class="max-w-28 truncate">{{ auth()->user()->username }}</span>
                        </a>
                        <a
                            href="{{ route('posts.create', auth()->user()->username) }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-3 py-2.5 text-sm font-bold text-white shadow-md shadow-sky-600/20 transition hover:bg-sky-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-sky-500/25 sm:px-4"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span class="hidden sm:inline">Crear Post</span>
                            <span class="sm:hidden">Crear</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 rounded-lg border border-orange-200 bg-orange-50 px-3 py-2.5 text-sm font-bold text-orange-700 transition hover:border-orange-300 hover:bg-orange-100 focus:outline-none focus:ring-4 focus:ring-orange-500/20 sm:px-4"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-3H9m0 0 3-3m-3 3 3 3" />
                                </svg>
                                <span class="hidden sm:inline">Cerrar sesión</span>
                            </button>
                        </form>
                    </nav>
                @endauth

                @guest
                    <nav class="flex items-center gap-2" aria-label="Navegación principal">
                        <a
                            class="rounded-lg px-3 py-2 text-sm font-bold text-gray-600 transition hover:bg-gray-100 hover:text-sky-600 focus:outline-none focus:ring-4 focus:ring-sky-500/15"
                            href="{{ route('login') }}"
                        >Iniciar sesión</a>
                        <a
                            class="rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-sky-600/20 transition hover:bg-sky-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                            href="{{ route('register') }}"
                        >Crear cuenta</a>
                    </nav>
                @endguest
            </div>
        </header>

        <main class="mx-auto w-full max-w-6xl flex-1 px-5 py-10 sm:px-8 lg:py-14">
            <h2 class="mb-10 text-center text-3xl font-black tracking-tight text-gray-950 sm:text-4xl">@yield('title')</h2>
            @yield('contenido')
        </main>

        <footer class="border-t border-gray-200 bg-white px-5 py-6 text-center text-sm text-gray-500 sm:px-8">
            <p class="font-semibold">
                DevStagram <span class="mx-1 text-gray-300">/</span> Una comunidad para quienes construyen en código
            </p>
            <p class="mt-2 font-medium">
                © {{ now()->year }} Todos los derechos reservados.
                <a
                    href="https://www.microweb-cr.es/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="font-bold text-sky-600 underline decoration-sky-300 underline-offset-4 transition hover:text-sky-800 hover:decoration-sky-800"
                >MicroWeb-cr</a>
            </p>
        </footer>

        <x-delete-post-modal />
    </body>
</html>
