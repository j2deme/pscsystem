<x-app-layout>
    <x-navbar></x-navbar>

    <div class="container mx-auto px-4 py-6">
        <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Solicitud de Alta de Usuario</h2>

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

            <div class="flex justify-center mb-6">
                @php
                    $tipoSeleccionado = request('tipo', 'noarmado');
                    $tabs = [
                        'armado' => 'Personal Armado',
                        'noarmado' => 'Personal No Armado',
                    ];
                @endphp

                @foreach ($tabs as $claveTipo => $label)
                    <a href="{{ route('sup.formAlta', ['tipo' => $claveTipo]) }}"
                    class="px-4 py-2 mx-1 rounded-t-lg border-b-2 {{ $tipoSeleccionado === $claveTipo ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            <form action="{{route('sup.guardarInfo')}}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="{{ request('tipo', 'noarmado') }}">
                <div class="form-group mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-600">Nombre(s)</label>
                    <input type="text" id="name" name="name" placeholder="Nombre completo" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="apellido_paterno" class="block text-sm font-semibold text-gray-600">Apellido Paterno</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno"  placeholder="Apellido Paterno" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>
                <div class="form-group mb-4">
                    <label for="apellido_materno" class="block text-sm font-semibold text-gray-600">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno"  placeholder="Apellido Materno" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
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
                    <input type="text" id="num_ext" name="num_ext" placeholder="Numero" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="colonia" class="block text-sm font-semibold text-gray-600">Domicilio Fiscal(Colonia)</label>
                    <input type="text" id="colonia" name="colonia" placeholder="Colonia" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="cp_fiscal" class="block text-sm font-semibold text-gray-600">CP Fiscal</label>
                    <input type="text" id="cp_fiscal" name="cp_fiscal" placeholder="CP Fiscal" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
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
                    <label for="liga_rfc" class="block text-sm font-semibold text-gray-600">Liga RFC</label>
                    <input type="text" id="liga_rfc" name="liga_rfc" placeholder="Liga RFC" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="domicilio_comprobante" class="block text-sm font-semibold text-gray-600">Domicilio de Comprobante</label>
                    <input type="text" id="domicilio_comprobante" name="domicilio_comprobante" placeholder="Domicilio de Comprobante" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="infonavit" class="block text-sm font-semibold text-gray-600">Infonavit (Opcional)</label>
                    <input type="text" id="infonavit" name="infonavit" placeholder="Infonavit" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="fonacot" class="block text-sm font-semibold text-gray-600">Fonacot (Opcional)</label>
                    <input type="text" id="fonacot" name="fonacot" placeholder="Fonacot" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="peso" class="block text-sm font-semibold text-gray-600">Peso</label>
                    <input type="text" id="peso" name="peso" placeholder="Peso" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="estatura" class="block text-sm font-semibold text-gray-600">Estatura</label>
                    <input type="text" id="estatura" name="estatura" placeholder="Estatura" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="rol" class="block text-sm font-semibold text-gray-600">Rol/Puesto</label>
                    <input type="text" id="rol" name="rol" placeholder="Rol" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="punto" class="block text-sm font-semibold text-gray-600">Punto</label>
                    <select id="punto" name="punto" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2">
                        <option disabled selected value="">Seleccione un subpunto</option>
                        @foreach($puntos as $punto)
                            <optgroup label="{{ $punto->nombre }}">
                                @foreach($punto->subpuntos as $subpunto)
                                    <option value="{{ $subpunto->nombre }}">
                                        @if($subpunto->codigo!= null)({{ str_pad($subpunto->codigo, 3, '0', STR_PAD_LEFT) }}) @endif {{ $subpunto->nombre }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-6">
                    <label for="reingreso" class="block text-sm font-semibold text-gray-600">Reingreso</label>
                    <input type="text" id="reingreso" name="reingreso" placeholder="Reingreso" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="empresa" class="block text-sm font-semibold text-gray-600">Empresa</label>
                    <select id="empresa" name="empresa" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2">
                        <option value="" disabled selected>Selecciona una empresa</option>
                        <option value="CPKC">CPKC</option>
                        <option value="SPYT">SPYT</option>
                        <option value="Montana">Montana</option>
                        <option value="PSC">PSC</option>
                    </select>
                    @error('empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-6">
                    <label for="sueldo_mensual" class="block text-sm font-semibold text-gray-600">Sueldo Mensual</label>
                    <input type="text" id="sueldo_mensual" name="sueldo_mensual" placeholder="Sueldo Mensual" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-6">
                    <label for="fecha_ingreso" class="block text-sm font-semibold text-gray-600">Fecha de Ingreso</label>
                    <input type="date" id="fecha_ingreso" name="fecha_ingreso" placeholder="Fecha de Ingreso" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" >
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>


                <div class="form-group">
                    <button type="submit" class="w-1/4 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                        Continuar
                    </button>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                        Regresar
                    </a>
                </div>
            </form>
            <p class="text-justify">Nota: Favor de llenar correctamente los campos requeridos, para posteriormente continuar con la subida de los documentos necesarios. <br>
            En caso de ser aceptada la solicitud, la contraseña para el nuevo usuario será su RFC.</p>
        </div>
    </div>
</x-app-layout>
