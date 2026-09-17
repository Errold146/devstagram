                    <article class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-md transition-all duration-300 hover:-translate-y-2 hover:border-sky-200 hover:shadow-2xl hover:shadow-sky-500/15">
                        @isset($activityLabel)
                            <div class="flex items-center gap-1.5 border-b border-gray-100 bg-sky-50/70 px-4 py-2 text-[11px] font-bold text-sky-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                </svg>
                                <span class="truncate">{{ $activityLabel }}</span>
                                @if (!empty($activityAt))
                                    <span class="text-sky-400">&middot; {{ $activityAt->diffForHumans() }}</span>
                                @endif
                            </div>
                        @endisset

                        <!-- Cabecera del Autor -->
                        <a href="{{ route('post.index', $post->user->username) }}" class="flex items-center gap-2 px-4 pt-4">
                            <div class="h-7 w-7 overflow-hidden rounded-full border border-gray-100 bg-gray-50">
                                <img
                                    src="{{ $post->user->profile_image ? asset('profiles/'.$post->user->profile_image) : asset('usuario.svg') }}"
                                    alt="Avatar de {{ $post->user->username }}"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <span class="text-xs font-bold text-gray-700 transition group-hover:text-sky-600">{{ $post->user->username }}</span>
                        </a>

                        <!-- Imagen con overlay en hover -->
                        <a href="{{ route('posts.show', ['user' => $post->user, 'post' => $post]) }}" class="relative mt-3 aspect-square overflow-hidden bg-gray-100 block">
                            <img
                                src="{{ asset('uploads') . '/' . $post->image }}"
                                alt="Imagen del post: {{ $post->title }}"
                                class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
                            />
                            <!-- Degradado suave al pasar el cursor -->
                            <div class="absolute inset-0 bg-linear-to-t from-gray-950/70 via-gray-900/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100 flex items-end p-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-bold text-sky-700 shadow-md backdrop-blur-md transition-transform duration-300 group-hover:translate-y-0 translate-y-4">
                                    Ver publicación &rarr;
                                </span>
                            </div>
                        </a>

                        <!-- Contenido de la tarjeta -->
                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <!-- Título del post -->
                                <h3 class="text-base font-bold text-gray-900 transition-colors group-hover:text-sky-600 line-clamp-1" title="{{ $post->title }}">
                                    <a href="{{ route('posts.show', ['user' => $post->user, 'post' => $post]) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Descripción acortada con Tooltip -->
                                <p class="mt-2 text-xs text-gray-500 leading-relaxed line-clamp-2 cursor-help" title="{{ $post->description }}">
                                    {{ \Illuminate\Support\Str::limit($post->description, 75) }}
                                </p>
                            </div>

                            <!-- Pie de tarjeta con botones de interacción -->
                            <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-3 text-xs">
                                <span class="text-[11px] font-medium text-gray-400">
                                    {{ $post->created_at ? $post->created_at->diffForHumans() : '' }}
                                </span>

                                <div class="flex items-center gap-1.5" data-like-widget>
                                    @auth
                                        <!-- Botón Me gusta -->
                                        <form action="{{ route('posts.likes.destroy', ['post' => $post]) }}" method="POST" class="js-like-form {{ $post->checkLike(auth()->id()) ? '' : 'hidden' }}" data-variant="liked">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-full bg-orange-500 p-1.5 text-white transition-all hover:scale-110 hover:bg-orange-600 active:scale-95 focus:outline-none"
                                                title="Quitar me gusta"
                                                aria-label="Quitar me gusta"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.575-4.683-4.575-1.761 0-3.315.932-4.14 2.308-.825-1.376-2.379-2.308-4.14-2.308-2.584 0-4.683 2.09-4.683 4.575 0 4.148 7.375 9.775 8.423 10.558a.75.75 0 00.836 0C13.625 18.025 21 12.398 21 8.25z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('posts.likes.store', ['post' => $post]) }}" method="POST" class="js-like-form {{ $post->checkLike(auth()->id()) ? 'hidden' : '' }}" data-variant="unliked">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-full bg-orange-50 p-1.5 text-orange-600 transition-all hover:scale-110 hover:bg-orange-500 hover:text-white active:scale-95 focus:outline-none"
                                                title="Me gusta"
                                                aria-label="Me gusta"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.575-4.683-4.575-1.761 0-3.315.932-4.14 2.308-.825-1.376-2.379-2.308-4.14-2.308-2.584 0-4.683 2.09-4.683 4.575 0 4.148 7.375 9.775 8.423 10.558a.75.75 0 00.836 0C13.625 18.025 21 12.398 21 8.25z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endauth

                                    <!-- Botón Compartir -->
                                    <button
                                        type="button"
                                        onclick="if (navigator.share) { navigator.share({ title: @js($post->title), url: @js(route('posts.show', ['user' => $post->user, 'post' => $post])) }); } else { navigator.clipboard.writeText(@js(route('posts.show', ['user' => $post->user, 'post' => $post]))).then(() => alert('¡Enlace copiado al portapapeles!')); }"
                                        class="inline-flex items-center justify-center rounded-full bg-sky-50 p-1.5 text-sky-600 transition-all hover:bg-sky-600 hover:text-white hover:scale-110 active:scale-95 focus:outline-none"
                                        title="Compartir"
                                        aria-label="Compartir"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0-10.628a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zm0 10.628a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                                        </svg>
                                    </button>

                                    @if (auth()->id() === (int) $post->user_id)
                                        <a
                                            href="{{ route('posts.edit', $post) }}"
                                            class="inline-flex items-center justify-center rounded-full bg-sky-50 p-1.5 text-sky-600 transition-all hover:scale-110 hover:bg-sky-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/25 active:scale-95"
                                            title="Editar publicación"
                                            aria-label="Editar publicación"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487a2.25 2.25 0 113.182 3.182L7.5 20.213 3 21l.787-4.5L16.862 4.487z" />
                                            </svg>
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
                                                class="inline-flex items-center justify-center rounded-full bg-red-50 p-1.5 text-red-600 transition-all hover:scale-110 hover:bg-red-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-red-500/25 active:scale-95"
                                                title="Eliminar publicación"
                                                aria-label="Eliminar publicación"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10.5 0v10.125A2.375 2.375 0 009.875 20h4.25a2.375 2.375 0 002.375-2.375V7.5m-7.5 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7.5m-5.25 3.75v4.5m4.5-4.5v4.5" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
