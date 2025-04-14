<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 sm:gap-4">
            <div class="flex items-center gap-2">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                    {{ Auth::user()->name }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-4">
                @if(Auth::user()->rol == 'admin')
                <x-admin-layout></x-admin-layout>
                @else
                <x-user-layout></x-user-layout>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Solicitud de Alta de Usuario</h2>

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

            <form action="#" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-600">Nombre(s)</label>
                    <input type="text" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="ApellidoPaterno" class="block text-sm font-semibold text-gray-600">Apellido Paterno</label>
                    <input type="text" id="ApellidoPaterno" name="ApellidoPaterno"  placeholder="Apellido Paterno" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>
                <div class="form-group mb-4">
                    <label for="ApellidoMaterno" class="block text-sm font-semibold text-gray-600">Apellido Materno</label>
                    <input type="text" id="ApellidoMaterno" name="ApellidoMaterno"  placeholder="Apellido Materno" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="curp" class="block text-sm font-semibold text-gray-600">CURP</label>
                    <input type="text" id="curp" name="curp" placeholder="CURP" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="NSS" class="block text-sm font-semibold text-gray-600">NSS</label>
                    <input type="text" id="NSS" name="NSS" placeholder="NSS" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="edo_civil" class="block text-sm font-semibold text-gray-600">Estado Civil</label>
                    <select id="edo_civil" name="edo_civil" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="Soltero">Soltero/a</option>
                        <option value="Casado">Casado/a</option>
                        <option value="Divorciado">Divorciado/a</option>
                        <option value="Divorciado">Viudo/a</option>
                        <option value="Union Civil">Unión civil</option>
                    </select>
                    @error('empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="RFC" class="block text-sm font-semibold text-gray-600">RFC</label>
                    <input type="text" id="RFC" name="RFC" placeholder="RFC" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>
                <div class="form-group mb-4">
                    <label for="telefono" class="block text-sm font-semibold text-gray-600">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Telefono" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="calle" class="block text-sm font-semibold text-gray-600">Domicilio (Calle)</label>
                    <input type="text" id="calle" name="calle" placeholder="Calle" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="num_ext" class="block text-sm font-semibold text-gray-600">Domicilio (Numero)</label>
                    <input type="text" id="num_ext" name="num_ext" placeholder="Numero" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="colonia" class="block text-sm font-semibold text-gray-600">Domicilio (Colonia)</label>
                    <input type="text" id="colonia" name="colonia" placeholder="Colonia" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="Ciudad" class="block text-sm font-semibold text-gray-600">Ciudad</label>
                    <input type="text" id="Ciudad" name="Ciudad" placeholder="Ciudad" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="estado" class="block text-sm font-semibold text-gray-600">Estado</label>
                    <input type="text" id="estado" name="estado" placeholder="Estado" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
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
                    <select id="empresa" name="empresa" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                        <option value="" disabled selected>Selecciona una empresa</option>
                        <option value="empresa_1">SPYT</option>
                        <option value="empresa_2">Montana</option>
                        <option value="empresa_3">PSC</option>
                    </select>
                    @error('empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2"required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                        Continuar
                    </button>
                </div>
            </form>
            <p class="text-justify">Nota: Favor de llenar correctamente los campos requeridos, para posteriormente continuar con la subida de los documentos necesarios. <br>
            En caso de ser aceptada la solicitud, la contraseña para el nuevo usuario será su RFC.</p>
        </div>
    </div>
</x-app-layout>
