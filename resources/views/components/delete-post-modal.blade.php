<div
    id="delete-modal"
    class="fixed inset-0 z-50 hidden items-center justify-center p-4 sm:p-6"
    role="dialog"
    aria-modal="true"
    aria-labelledby="delete-modal-title"
    aria-describedby="delete-modal-description"
>
    <div class="absolute inset-0 bg-gray-950/55 backdrop-blur-sm" data-delete-modal-cancel></div>

    <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/70 bg-white shadow-2xl shadow-gray-950/25">
        <div class="absolute inset-x-0 top-0 h-1 bg-linear-to-r from-orange-400 via-red-500 to-sky-500"></div>

        <div class="p-6 sm:p-7">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-600 ring-8 ring-red-50/60">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-6" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376L10.697 4.5a1.5 1.5 0 012.606 0l8 11.626A1.5 1.5 0 0120.001 18.5H3.999a1.5 1.5 0 01-1.302-2.124zM12 16.5h.008v.008H12V16.5z" />
                    </svg>
                </div>

                <div class="min-w-0 pt-1">
                    <h2 id="delete-modal-title" class="text-lg font-black text-gray-950 sm:text-xl">¿Eliminar elemento?</h2>
                    <p id="delete-modal-description" class="mt-2 text-sm leading-relaxed text-gray-500">Esta acción no se puede deshacer.</p>
                </div>

                <button
                    type="button"
                    class="ml-auto shrink-0 rounded-xl p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-4 focus:ring-sky-500/15"
                    aria-label="Cerrar ventana de confirmación"
                    data-delete-modal-cancel
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-600 shadow-sm transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-500/10"
                    data-delete-modal-cancel
                >
                    Cancelar
                </button>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-red-600/20 transition hover:bg-red-700 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-red-500/25"
                    data-delete-modal-confirm
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5h12m-10.5 0v10.125A2.375 2.375 0 009.875 20h4.25a2.375 2.375 0 002.375-2.375V7.5m-7.5 0V5.25A1.25 1.25 0 0110.25 4h3.5A1.25 1.25 0 0115 5.25V7.5m-5.25 3.75v4.5m4.5-4.5v4.5" />
                    </svg>
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
