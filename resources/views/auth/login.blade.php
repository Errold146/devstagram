@extends('layouts.app')

@section('title')
    Inicia Sesión en DevStagram
@endsection

@section('contenido')
    <div class="mx-auto grid max-w-5xl items-stretch gap-6 lg:grid-cols-2 lg:gap-8">
        <div class="group relative min-h-112 overflow-hidden rounded-2xl bg-gray-950 shadow-xl shadow-sky-950/10">
            <img
                src="{{ asset('img/login.jpg') }}"
                alt="Persona programando"
                class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
            >
            <div class="absolute inset-0 bg-gray-950/55"></div>
            <div class="relative flex h-full flex-col justify-between p-8 text-white sm:p-10">
                <span class="w-fit rounded-full border border-white/30 bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-sky-200 backdrop-blur-sm">
                    Tu espacio para crear
                </span>

                <div class="max-w-sm">
                    <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-sky-300">DevStagram</p>
                    <h3 class="text-3xl font-black leading-tight sm:text-4xl">Comparte lo que construyes.</h3>
                    <p class="mt-4 text-sm leading-6 text-gray-200">
                        Comparte ideas, repositorios de GitHub y encuentra personas para construir juntos.
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xl shadow-gray-900/5 sm:p-8">
            <div class="mb-8">
                <p class="mb-2 text-sm font-bold uppercase tracking-[0.18em] text-sky-600">Inica Sesión</p>
                <h3 class="text-2xl font-black text-gray-900">Tu comunidad empieza aquí</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">Inicia sesión y empieza a colaborar y descubrir proyectos.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
                @csrf

                @if (session('mensaje'))
                    <p class="my-2 text-red-500">{{ session('mensaje') }}</p>
                @endif

                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Tu Email de registro"
                        autocomplete="email"
                        required
                        class="w-full rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                        value="{{ old('email') }}"
                    />

                    @error('email')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-bold text-gray-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Tu Password"
                        autocomplete="new-password"
                        required
                        class="w-full rounded-xl border bg-gray-50 px-4 py-3 text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:bg-white focus:ring-4 focus:ring-sky-500/15 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-200 focus:border-sky-500' }}"
                    />
                    @error('password')
                        <p class="my-2 text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        @checked(old('remember'))
                    />
                    <label for="remember" class="text-sm font-bold text-gray-700">Mantener Sesión Abierta</label>
                </div>

                <input
                    type="submit"
                    value="Iniciar Sesión"
                    class="mt-3 w-full cursor-pointer rounded-xl bg-sky-600 px-4 py-3.5 font-bold uppercase tracking-wide text-white shadow-lg shadow-sky-600/20 transition duration-300 hover:bg-sky-700 hover:shadow-sky-600/35 focus:outline-none focus:ring-4 focus:ring-sky-500/25"
                />
            </form>
        </div>
    </div>
@endsection
