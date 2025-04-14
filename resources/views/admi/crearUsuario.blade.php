<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-2">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                    {{ Auth::user()->name }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-4">
                <x-admin-layout></x-admin-layout>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Registrar Nuevo Usuario</h2>

            @if(session('success'))
                <div class="alert alert-success bg-green-200 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error bg-red-200 text-red-800 p-4 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('registrarUsuario') }}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-600">Nombre Completo</label>
                    <input type="text" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="rol" class="block text-sm font-semibold text-gray-600">Rol/Puesto/Departamento</label>
                    <input type="rol" id="rol" name="rol" placeholder="Rol" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="punto" class="block text-sm font-semibold text-gray-600">Punto</label>
                    <input type="punto" id="punto" name="punto" placeholder="Punto" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="empresa" class="block text-sm font-semibold text-gray-600">Empresa</label>
                    <input type="empresa" id="emrpesa" name="empresa" placeholder="Empresa" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2"required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-600">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Contraseña" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-600">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirmar contraseña" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                        Registrar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
