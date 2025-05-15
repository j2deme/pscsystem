<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Formulario de Alta de Usuario</h2>

                @if(session('success'))
                    <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                @endif
                @if(session('error'))
                    <div class="alert alert-error bg-red-200 text-red-800 p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

            <form action="{{route('rh.guardarAlta')}}" method="POST">
                @csrf

                <div class="form-group mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-600">Nombre(s)</label>
                    <input type="text" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="apellido_paterno" class="block text-sm font-semibold text-gray-600">Apellido Paterno</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno"  placeholder="Apellido Paterno" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>
                <div class="form-group mb-4">
                    <label for="apellido_materno" class="block text-sm font-semibold text-gray-600">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno"  placeholder="Apellido Materno" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="fecha_nacimiento" class="block text-sm font-semibold text-gray-600">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="fecha de nacimiento" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="curp" class="block text-sm font-semibold text-gray-600" minlength="18" maxlength="18">CURP</label>
                    <input type="text" id="curp" name="curp" placeholder="CURP" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="nss" class="block text-sm font-semibold text-gray-600" minlength="11" maxlength="11">NSS</label>
                    <input type="text" id="nss" name="nss" placeholder="NSS" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="edo_civil" class="block text-sm font-semibold text-gray-600">Estado Civil</label>
                    <select id="edo_civil" name="edo_civil" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="Soltero">Soltero/a</option>
                        <option value="Casado">Casado/a</option>
                        <option value="Divorciado">Divorciado/a</option>
                        <option value="Viudo">Viudo/a</option>
                        <option value="Union Civil">Unión civil</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="rfc" class="block text-sm font-semibold text-gray-600" minlength="13" maxlength="13">RFC</label>
                    <input type="text" id="rfc" name="rfc" placeholder="RFC" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>
                <div class="form-group mb-4">
                    <label for="telefono" class="block text-sm font-semibold text-gray-600">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Telefono" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>
                <div class="form-group mb-4">
                    <label for="calle" class="block text-sm font-semibold text-gray-600">Domicilio Fiscal (Calle)</label>
                    <input type="text" id="calle" name="calle" placeholder="Calle" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="num_ext" class="block text-sm font-semibold text-gray-600">Domicilio Fiscal(Numero)</label>
                    <input type="number" id="num_ext" name="num_ext" placeholder="Numero" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="colonia" class="block text-sm font-semibold text-gray-600">Domicilio Fiscal(Colonia)</label>
                    <input type="text" id="colonia" name="colonia" placeholder="Colonia" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="ciudad" class="block text-sm font-semibold text-gray-600">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="estado" class="block text-sm font-semibold text-gray-600">Estado</label>
                    <input type="text" id="estado" name="estado" placeholder="Estado" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="domicilio_comprobante" class="block text-sm font-semibold text-gray-600">Domicilio de Comprobante</label>
                    <input type="text" id="domicilio_comprobante" name="domicilio_comprobante" placeholder="Domicilio de Comprobante" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="imss" class="block text-sm font-semibold text-gray-600">IMSS</label>
                    <input type="text" id="imss" name="imss" placeholder="Imss" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2">
                </div>

                <div class="form-group mb-4">
                    <label for="infonavit" class="block text-sm font-semibold text-gray-600">Infonavit (Opcional)</label>
                    <input type="text" id="infonavit" name="infonavit" placeholder="Infonavit" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="fonacot" class="block text-sm font-semibold text-gray-600">Fonacot (Opcional)</label>
                    <input type="text" id="fonacot" name="fonacot" placeholder="Fonacot" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>
                @if (Auth::user()->rol == 'Recursos Humanos' || Auth::user()->departamento == 'Recursos Humanos' || Auth::user()->rol == 'Administrador' )
                <div class="form-group mb-6">
                    <label for="departamento" class="block text-sm font-semibold text-gray-600">Departamento</label>
                    <select id="departamento" name="departamento" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="Recursos Humanos">Recursos Humanos</option>
                        <option value="Nóminas">Nóminas</option>
                        <option value="Jurídico">Jurídico</option>
                        <option value="IMSS">IMSS</option>
                        <option value="Monitoreo">Monitoreo</option>
                        <option value="Custodios">Custodios</option>
                        <option value="Tesorería">Compras</option>
                    </select>
                </div>
            @endif

                <div class="form-group mb-6">
                    <label for="rol" class="block text-sm font-semibold text-gray-600">Rol/Puesto</label>
                    <input type="rol" id="rol" name="rol" placeholder="Rol" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="punto" class="block text-sm font-semibold text-gray-600">Punto</label>
                    <input type="punto" id="punto" name="punto" placeholder="Punto" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="empresa" class="block text-sm font-semibold text-gray-600">Empresa</label>
                    <select id="empresa" name="empresa" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                        <option value="" disabled selected>Selecciona una empresa</option>
                        <option value="CPKC">CPKC</option>
                        <option value="SPYT">SPYT</option>
                        <option value="Montana">Montana</option>
                        <option value="PSC">PSC</option>
                    </select>
                    @error('empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group flex items-center justify-center">
                    <button type="submit" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">
                        Continuar
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 ml-2 mr-2">
                        Regresar
                    </a>
                </div>
            </form>
            <p class="text-justify">Nota: Favor de llenar correctamente los campos requeridos, para posteriormente continuar con la subida de los documentos necesarios. <br></p>
            </div>
        </div>
    </div>
</x-app-layout>
