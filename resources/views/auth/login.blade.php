<x-guest-layout>
        <!-- Contenedor del login -->
        <div class="w-full max-w-md bg-white shadow-lg rounded-2xl p-8 md:p-10 border border-gray-200">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-semibold text-blue-700">Acceso al sistema</h2>
                <p class="text-gray-500 text-sm mt-2">Ingrese sus credenciales para continuar</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Correo electrónico')" class="text-gray-700" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                        class="mt-1 block w-full px-4 py-2 bg-blue-50 border border-blue-100 rounded-md text-gray-800 focus:ring-blue-400 focus:border-blue-400" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Contraseña')" class="text-gray-700" />
                    <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                        class="mt-1 block w-full px-4 py-2 bg-blue-50 border border-blue-100 rounded-md text-gray-800 focus:ring-blue-400 focus:border-blue-400" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring focus:ring-blue-300"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                    </label>
                </div>

                <!-- Botón y enlace -->
                <div class="flex items-center justify-between mb-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif

                    <x-primary-button class="ml-3 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                        {{ __('Iniciar sesión') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
</x-guest-layout>
