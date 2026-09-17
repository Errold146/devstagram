@extends('layouts.app')

@section('title')
    Perfil: {{ $user->username }}
@endsection

@section('contenido')
    <!-- Perfil de usuario -->
    <div class="flex justify-center">
        <div class="w-full max-w-4xl rounded-3xl border border-gray-100 bg-white p-6 md:p-8 shadow-xl shadow-sky-500/5">
            <div class="flex flex-col items-center gap-6 md:flex-row md:gap-10">
                <!-- Avatar con anillo degradado -->
                <div class="relative group">
                    <div class="absolute -inset-1 rounded-full bg-linear-to-r from-sky-500 via-orange-400 to-sky-600 opacity-75 blur transition duration-500 group-hover:opacity-100"></div>
                    <div class="relative h-28 w-28 overflow-hidden rounded-full border-2 border-white bg-gray-50 p-1 shadow-md md:h-36 md:w-36">
                        <img
                            src="{{ $user->profile_image ? asset('profiles/'.$user->profile_image) : asset('usuario.svg') }}"
                            alt="Imagen Usuario {{ $user->username }}"
                            class="h-full w-full object-cover"
                            data-user-avatar
                        />
                    </div>

                    @auth
                        @if (auth()->id() === (int) $user->id)
                            <button
                                type="button"
                                id="profile-edit-toggle"
                                class="absolute bottom-1 right-1 flex h-8 w-8 items-center justify-center rounded-full bg-sky-600 text-white shadow-md ring-2 ring-white transition-all hover:scale-110 hover:bg-sky-700 focus:outline-none"
                                title="Editar perfil"
                                aria-label="Editar perfil"
                                aria-haspopup="dialog"
                                aria-controls="profile-edit-modal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                                </svg>
                            </button>
                        @endif
                    @endauth
                </div>

                <!-- Detalles e información del usuario -->
                <div class="flex flex-1 flex-col items-center md:items-start text-center md:text-left space-y-4">
                    <div>
                        <span class="inline-block rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-600 uppercase tracking-wider mb-1">
                            {{ $user->occupation ?: 'Desarrollador' }}
                        </span>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 md:text-4xl">
                            {{ $user->username }}
                        </h1>
                    </div>

                    <!-- Estadísticas -->
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 pt-1">
                        <button
                            type="button"
                            id="followers-toggle"
                            class="flex items-center gap-2 rounded-xl bg-gray-50 px-4 py-2 border border-gray-100 shadow-sm transition hover:border-sky-200 hover:bg-sky-50"
                            aria-haspopup="dialog"
                            aria-controls="followers-modal"
                        >
                            <span class="text-lg font-black text-sky-600">{{ $followersCount }}</span>
                            <span class="text-xs font-medium text-gray-600">@choice('Seguidor|Seguidores', $followersCount)</span>
                        </button>
                        <button
                            type="button"
                            id="following-toggle"
                            class="flex items-center gap-2 rounded-xl bg-gray-50 px-4 py-2 border border-gray-100 shadow-sm transition hover:border-sky-200 hover:bg-sky-50"
                            aria-haspopup="dialog"
                            aria-controls="following-modal"
                        >
                            <span class="text-lg font-black text-sky-600">{{ $followingCount }}</span>
                            <span class="text-xs font-medium text-gray-600">Siguiendo</span>
                        </button>
                        <div class="flex items-center gap-2 rounded-xl bg-orange-50 px-4 py-2 border border-orange-100 shadow-sm">
                            <span class="text-lg font-black text-orange-500">{{ $posts->total() }}</span>
                            <span class="text-xs font-medium text-orange-700">Publicaciones</span>
                        </div>

                        <div>
                            @auth
                                @if ($user->id !== auth()->id())
                                    @if ($isFollowing)
                                        <form action="{{ route('users.unfollow', $user->username) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-3.5 py-2 text-xs font-bold text-white shadow-md transition hover:bg-orange-500 hover:shadow-lg"
                                            >
                                                Dejar de Seguir
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('users.follow', $user->username) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-3.5 py-2 text-xs font-bold text-white shadow-md transition hover:bg-sky-500 hover:shadow-lg"
                                            >
                                                Seguir
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal de Seguidores -->
    <div
        id="followers-modal"
        class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="followers-modal-title"
    >
        <div class="relative flex max-h-[80vh] w-full max-w-md flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 id="followers-modal-title" class="text-lg font-black text-gray-900">Seguidores</h3>
                <button type="button" id="followers-close" class="rounded-full bg-gray-100 p-2 text-gray-500 transition hover:bg-gray-200" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 space-y-1 overflow-y-auto p-3">
                @forelse ($followers as $follower)
                    <a href="{{ route('post.index', $follower->username) }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-sky-50">
                        <img
                            src="{{ $follower->profile_image ? asset('profiles/'.$follower->profile_image) : asset('usuario.svg') }}"
                            alt="Avatar de {{ $follower->username }}"
                            class="h-10 w-10 rounded-full border border-gray-100 object-cover"
                        />
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $follower->username }}</p>
                            <p class="text-xs text-gray-500">{{ $follower->name }}</p>
                        </div>
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-gray-400">Aún no tiene seguidores.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Modal de Siguiendo -->
    <div
        id="following-modal"
        class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="following-modal-title"
    >
        <div class="relative flex max-h-[80vh] w-full max-w-md flex-col overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h3 id="following-modal-title" class="text-lg font-black text-gray-900">Siguiendo</h3>
                <button type="button" id="following-close" class="rounded-full bg-gray-100 p-2 text-gray-500 transition hover:bg-gray-200" aria-label="Cerrar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="flex-1 space-y-1 overflow-y-auto p-3">
                @forelse ($following as $followedUser)
                    <a href="{{ route('post.index', $followedUser->username) }}" class="flex items-center gap-3 rounded-xl px-3 py-2 transition hover:bg-sky-50">
                        <img
                            src="{{ $followedUser->profile_image ? asset('profiles/'.$followedUser->profile_image) : asset('usuario.svg') }}"
                            alt="Avatar de {{ $followedUser->username }}"
                            class="h-10 w-10 rounded-full border border-gray-100 object-cover"
                        />
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $followedUser->username }}</p>
                            <p class="text-xs text-gray-500">{{ $followedUser->name }}</p>
                        </div>
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-gray-400">Aún no sigue a nadie.</p>
                @endforelse
            </div>
        </div>
    </div>

    @auth
        @if (auth()->id() === (int) $user->id)
            <!-- Modal de Edición de Perfil -->
            <div
                id="profile-edit-modal"
                class="{{ $errors->hasAny(['name', 'username', 'occupation']) ? 'flex' : 'hidden' }} fixed inset-0 z-50 items-center justify-center bg-gray-950/60 p-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="profile-edit-modal-title"
            >
                <div class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-3xl bg-white shadow-2xl">
                    <!-- Cabecera degradada -->
                    <div class="relative overflow-hidden rounded-t-3xl bg-linear-to-r from-sky-600 via-sky-500 to-orange-500 px-6 py-5">
                        <div class="absolute -right-6 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
                        <div class="absolute -left-8 bottom-0 h-24 w-24 rounded-full bg-white/10 blur-2xl"></div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-widest text-white/70">Tu perfil</p>
                                <h3 id="profile-edit-modal-title" class="text-xl font-black text-white">Editar información</h3>
                            </div>
                            <button type="button" id="profile-edit-close" class="rounded-full bg-white/15 p-2 text-white transition hover:bg-white/25" aria-label="Cerrar">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6 p-6">
                        <!-- Imagen de perfil -->
                        <div>
                            <div class="mb-3 flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5l.75-1.5h9l.75 1.5m-11.25 0h12A1.5 1.5 0 0120.25 9v9.75a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V9a1.5 1.5 0 011.5-1.5zM15.75 13.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-extrabold text-gray-900">Tu imagen de perfil</p>
                                    <p class="text-xs text-gray-500">JPG, PNG, WEBP o GIF hasta 5 MB</p>
                                </div>
                            </div>
                            <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" id="profile-dropzone" class="dropzone min-h-28 rounded-xl border-2 border-dashed border-sky-200 bg-sky-50/40 p-3">
                                @csrf
                                <div class="dz-message text-xs font-bold text-sky-600">Arrastra una imagen o haz clic para cambiarla</div>
                            </form>
                        </div>

                        <div class="h-px bg-gray-100"></div>

                        <!-- Datos personales -->
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">Nombre</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full rounded-xl border bg-gray-50/70 px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('name') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                                />
                                @error('name')
                                    <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="username" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">Usuario</label>
                                <input
                                    type="text"
                                    id="username"
                                    name="username"
                                    value="{{ old('username', $user->username) }}"
                                    class="w-full rounded-xl border bg-gray-50/70 px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('username') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                                />
                                @error('username')
                                    <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="occupation" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-700">Ocupación</label>
                                <input
                                    type="text"
                                    id="occupation"
                                    name="occupation"
                                    placeholder="Ej. Desarrollador Full Stack"
                                    value="{{ old('occupation', $user->occupation) }}"
                                    class="w-full rounded-xl border bg-gray-50/70 px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('occupation') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                                />
                                @error('occupation')
                                    <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-sky-600 to-orange-500 px-5 py-3 text-sm font-bold uppercase tracking-wide text-white shadow-md shadow-sky-600/20 transition duration-300 hover:shadow-lg hover:shadow-sky-600/30 focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Guardar cambios
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <!-- Sección de Publicaciones -->
    <section class="container mx-auto mt-14 px-4 sm:px-6">
        <div class="mb-10 flex flex-col items-center justify-center gap-2 text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-100 px-3 py-1 text-xs font-bold text-orange-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                Galería
            </span>
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Publicaciones
            </h2>
            <div class="h-1 w-16 rounded-full bg-linear-to-r from-sky-500 to-orange-500 mt-1"></div>
        </div>

        @if ($posts->count())
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($posts as $post)
                    @include('posts.card', ['post' => $post])
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $posts->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-gray-200 bg-white p-12 text-center shadow-sm">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-orange-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No hay publicaciones aún</h3>
                <p class="mt-1 text-sm text-gray-500 max-w-sm">Este usuario aún no ha compartido ninguna publicación en su perfil.</p>
            </div>
        @endif
    </section>
@endsection
