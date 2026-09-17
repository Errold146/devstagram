@props(['comment', 'post', 'user', 'depth' => 0])

<div id="comment-{{ $comment->id }}" class="{{ $depth > 0 ? 'ml-4 border-l-2 border-sky-100 pl-4 sm:ml-8' : '' }}">
    <div class="group flex items-start gap-3">
        <div class="relative shrink-0">
            <div class="absolute -inset-0.5 rounded-full bg-linear-to-r from-sky-500 to-orange-500 opacity-60 blur-[2px]"></div>
            <div class="relative flex h-9 w-9 items-center justify-center overflow-hidden rounded-full border-2 border-white bg-gray-50">
                <img
                    src="{{ $comment->user->profile_image ? asset('profiles/'.$comment->user->profile_image) : asset('usuario.svg') }}"
                    alt="Avatar de {{ $comment->user->username }}"
                    class="h-full w-full object-cover"
                    data-user-avatar
                />
            </div>
        </div>

        <div class="min-w-0 flex-1">
            <div class="rounded-2xl rounded-tl-sm border border-gray-100 bg-gray-50/70 px-4 py-2.5 shadow-sm transition group-hover:border-sky-100 group-hover:bg-sky-50/40">
                <div class="flex items-center justify-between gap-2">
                    <a
                        href="{{ route('post.index', $comment->user->username) }}"
                        class="text-xs font-bold text-gray-900 transition hover:text-sky-600 focus:outline-none focus:ring-2 focus:ring-sky-500/25"
                    >
                        {{ $comment->user->username }}
                    </a>
                    <div class="flex items-center gap-2">
                        <span class="text-[0.65rem] font-medium text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                        @auth
                            @if (auth()->id() === (int) $comment->user_id)
                                <form
                                    action="{{ route('comments.destroy', $comment) }}"
                                    method="POST"
                                    data-delete-form
                                    data-delete-title="¿Eliminar comentario?"
                                    data-delete-description="Esta acción eliminará este comentario y todas sus respuestas. No se puede deshacer."
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="inline-flex rounded-lg p-1 text-gray-400 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500/25"
                                        title="Eliminar comentario"
                                        aria-label="Eliminar comentario"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10.5 0v10.125A2.375 2.375 0 009.875 20h4.25a2.375 2.375 0 002.375-2.375V7.5m-7.5 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7.5m-5.25 3.75v4.5m4.5-4.5v4.5" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
                <p class="mt-1 text-sm leading-relaxed wrap-break-word text-gray-700">{{ $comment->comment }}</p>
            </div>

            @auth
                <button
                    type="button"
                    onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')"
                    class="mt-1.5 inline-flex items-center gap-1 text-[0.7rem] font-bold text-sky-600 transition hover:text-sky-700"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17l-5-5m0 0l5-5m-5 5h12a4 4 0 014 4v1" />
                    </svg>
                    Responder
                </button>

                <form
                    id="reply-form-{{ $comment->id }}"
                    action="{{ route('comments.store', ['post' => $post, 'user' => $user]) }}"
                    method="POST"
                    class="js-comment-form mt-2 hidden"
                >
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <div class="flex items-end gap-2">
                        <textarea
                            name="comment"
                            rows="1"
                            placeholder="Responde a {{ $comment->user->username }}..."
                            class="w-full resize-none rounded-xl border border-gray-200 bg-white p-2.5 text-xs text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-500/15"
                        ></textarea>
                        <button
                            type="submit"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-sky-600 p-2.5 text-white shadow-sm transition hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                            title="Enviar respuesta"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </div>
                </form>
            @endauth

            @if ($depth === 0)
                <div id="replies-{{ $comment->id }}" class="mt-3 space-y-3 {{ $comment->replies->isEmpty() ? 'hidden' : '' }}">
                    @foreach ($comment->allRepliesFlattened() as $reply)
                        <x-comment :comment="$reply" :post="$post" :user="$user" :depth="1" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
