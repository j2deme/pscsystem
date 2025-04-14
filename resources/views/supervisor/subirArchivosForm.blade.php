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

            <form action="{{ route('sup.guardarArchivos', $solicitud->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h2 class="text-lg font-semibold mb-4">Documentos Obligatorios</h2>

                <div class="mb-4">
                    <label class="block font-medium">Acta de Nacimiento *</label>
                    <input type="file" name="arch_acta_nacimiento" required class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">CURP *</label>
                    <input type="file" name="arch_curp" required class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">INE *</label>
                    <input type="file" name="arch_ine" required class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Comprobante de Domicilio *</label>
                    <input type="file" name="arch_comprobante_domicilio" required class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">RFC *</label>
                    <input type="file" name="arch_rfc" required class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Comprobante de Estudios *</label>
                    <input type="file" name="arch_comprobante_estudios" required class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Fotografía (Reciente) *</label>
                    <input type="file" name="arch_foto" required class="block mt-1">
                </div>

                <h2 class="text-lg font-semibold mt-6 mb-4">Documentos Opcionales</h2>

                <div class="mb-4">
                    <label class="block font-medium">Carta de Recomendación Laboral</label>
                    <input type="file" name="arch_carta_rec_laboral" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Carta de Recomendación Personal</label>
                    <input type="file" name="arch_carta_rec_personal" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Cartilla Militar</label>
                    <input type="file" name="arch_cartilla_militar" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Comprobante INFONAVIT</label>
                    <input type="file" name="arch_infonavit" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Comprobante FONACOT</label>
                    <input type="file" name="arch_fonacot" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Licencia de Conducir</label>
                    <input type="file" name="arch_licencia_conducir" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Carta de Antecedentes No Penales</label>
                    <input type="file" name="arch_carta_no_penales" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Visa</label>
                    <input type="file" name="visa" class="block mt-1">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Pasaporte</label>
                    <input type="file" name="pasaporte" class="block mt-1">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Subir Documentos
                </button>
                <a href="{{ route('sup.nuevoUsuarioForm') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                    Regresar
                </a>
            </form>
        </div>
    </div>
</x-app-layout>
