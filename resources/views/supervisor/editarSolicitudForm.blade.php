<x-app-layout>
    <x-navbar></x-navbar>

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

            <form action="{{route('sup.editarInformacionSolicitud', $solicitud->id)}}" method="POST">
                @csrf
                <div class="form-group mb-4">
                    <label for="name" class="block text-sm font-semibold text-gray-600">Nombre(s)</label>
                    <input type="text" id="name" name="name" placeholder="Nombre completo" value="{{ $solicitud->nombre }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="apellido_paterno" class="block text-sm font-semibold text-gray-600">Apellido Paterno</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno"  placeholder="Apellido Paterno" value="{{ $solicitud->apellido_paterno }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>
                <div class="form-group mb-4">
                    <label for="apellido_materno" class="block text-sm font-semibold text-gray-600">Apellido Materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno"  placeholder="Apellido Materno" value="{{ $solicitud->apellido_materno }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="fecha_nacimiento" class="block text-sm font-semibold text-gray-600">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="fecha de nacimiento" value="{{ $solicitud->fecha_nacimiento }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="curp" class="block text-sm font-semibold text-gray-600">CURP</label>
                    <input type="text" id="curp" name="curp" placeholder="CURP" value="{{ $solicitud->curp }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="nss" class="block text-sm font-semibold text-gray-600">NSS</label>
                    <input type="text" id="nss" name="nss" placeholder="NSS" value="{{ $solicitud->nss }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="edo_civil" class="block text-sm font-semibold text-gray-600">Estado Civil</label>
                    <select id="edo_civil" name="edo_civil" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                        <option value="" disabled {{ is_null($solicitud->estado_civil) ? 'selected' : '' }}>Selecciona una opción</option>
                        <option value="Soltero" {{ $solicitud->estado_civil === 'Soltero' ? 'selected' : '' }}>Soltero/a</option>
                        <option value="Casado" {{ $solicitud->estado_civil === 'Casado' ? 'selected' : '' }}>Casado/a</option>
                        <option value="Divorciado" {{ $solicitud->estado_civil === 'Divorciado' ? 'selected' : '' }}>Divorciado/a</option>
                        <option value="Viudo" {{ $solicitud->estado_civil === 'Viudo' ? 'selected' : '' }}>Viudo/a</option>
                        <option value="Union Civil" {{ $solicitud->estado_civil === 'Union Civil' ? 'selected' : '' }}>Unión civil</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="rfc" class="block text-sm font-semibold text-gray-600">RFC</label>
                    <input type="text" id="rfc" name="rfc" placeholder="RFC" value="{{ $solicitud->rfc }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>
                <div class="form-group mb-4">
                    <label for="telefono" class="block text-sm font-semibold text-gray-600">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" placeholder="Telefono" value="{{ $solicitud->telefono }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="calle" class="block text-sm font-semibold text-gray-600">Domicilio (Calle)</label>
                    <input type="text" id="calle" name="calle" placeholder="Calle" value="{{ $solicitud->domicilio_calle }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="num_ext" class="block text-sm font-semibold text-gray-600">Domicilio (Numero)</label>
                    <input type="number" id="num_ext" name="num_ext" placeholder="Numero" value="{{ $solicitud->domicilio_numero }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="colonia" class="block text-sm font-semibold text-gray-600">Domicilio (Colonia)</label>
                    <input type="text" id="colonia" name="colonia" placeholder="Colonia" value="{{ $solicitud->domicilio_colonia }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="ciudad" class="block text-sm font-semibold text-gray-600">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad" value="{{ $solicitud->domicilio_ciudad }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-4">
                    <label for="estado" class="block text-sm font-semibold text-gray-600">Estado</label>
                    <input type="text" id="estado" name="estado" placeholder="Estado" value="{{ $solicitud->domicilio_estado }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="rol" class="block text-sm font-semibold text-gray-600">Rol/Puesto/Departamento</label>
                    <input type="rol" id="rol" name="rol" placeholder="Rol" value="{{ $solicitud->rol }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="punto" class="block text-sm font-semibold text-gray-600">Punto</label>
                    <input type="punto" id="punto" name="punto" placeholder="Punto" value="{{ $solicitud->punto }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                </div>

                <div class="form-group mb-6">
                    <label for="empresa" class="block text-sm font-semibold text-gray-600">Empresa</label>
                    <select id="empresa" name="empresa" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2" required>
                        <option value="" disabled {{ is_null($solicitud->empresa) ? 'selected' : '' }}>Selecciona una empresa</option>
                        <option value="CPKC" {{ $solicitud->empresa === 'CPKC' ? 'selected' : '' }}>CPKC</option>
                        <option value="SPYT" {{ $solicitud->empresa === 'SPYT' ? 'selected' : '' }}>SPYT</option>
                        <option value="Montana" {{ $solicitud->empresa === 'Montana' ? 'selected' : '' }}>Montana</option>
                        <option value="PSC" {{ $solicitud->empresa === 'PSC' ? 'selected' : '' }}>PSC</option>
                    </select>
                    @error('empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-600">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Correo electrónico" value="{{ $solicitud->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-md mt-2"required>
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="w-1/4 bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                        Continuar
                    </button>
                    <a href="{{ route('sup.solicitud.detalle', $solicitud->id) }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                        Regresar
                    </a>
                </div>
            </form>
            <p class="text-justify">Nota: Favor de llenar correctamente los campos requeridos, para posteriormente continuar con la subida de los documentos necesarios. <br>
            En caso de ser aceptada la solicitud, la contraseña para el nuevo usuario será su RFC.</p>
        </div>
    </div>
</x-app-layout>
