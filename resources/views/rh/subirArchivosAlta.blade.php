<x-app-layout>
    <x-navbar />

    <div class="py-8 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 space-y-6">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Archivos Para Alta de Usuario</h2>

                <form action="{{ route('rh.guardarArchivosAlta', $solicitud->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-2">Documentos Obligatorios</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @php
                            $documentosObligatorios = [
                                ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
                                ['label' => 'CURP', 'name' => 'arch_curp'],
                                ['label' => 'INE', 'name' => 'arch_ine'],
                                ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
                                ['label' => 'RFC', 'name' => 'arch_rfc'],
                                ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
                                ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
                            ];
                        @endphp

                        @foreach ($documentosObligatorios as $doc)
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                    {{ $doc['label'] }} <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="{{ $doc['name'] }}" required class="w-full text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm file:py-1 file:px-2 file:border file:border-gray-300 file:rounded file:bg-gray-100 dark:file:bg-gray-600 file:text-gray-700 dark:file:text-gray-200">
                            </div>
                        @endforeach
                    </div>

                    <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mt-10 mb-2">Documentos Opcionales</h3>
                    <div class="grid grid-cols-2 gap-6">
                        @php
                            $documentosOpcionales = [
                                ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
                                ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
                                ['label' => 'Cartilla Militar', 'name' => 'arch_cartilla_militar'],
                                ['label' => 'Comprobante INFONAVIT', 'name' => 'arch_infonavit'],
                                ['label' => 'Comprobante FONACOT', 'name' => 'arch_fonacot'],
                                ['label' => 'Licencia de Conducir', 'name' => 'arch_licencia_conducir'],
                                ['label' => 'Carta de Antecedentes No Penales', 'name' => 'arch_carta_no_penales'],
                                ['label' => 'Visa', 'name' => 'visa'],
                                ['label' => 'Pasaporte', 'name' => 'pasaporte'],
                            ];
                        @endphp

                        @foreach ($documentosOpcionales as $doc)
                            <div>
                                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-1">
                                    {{ $doc['label'] }}
                                </label>
                                <input type="file" name="{{ $doc['name'] }}" class="w-full text-sm bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm file:py-1 file:px-2 file:border file:border-gray-300 file:rounded file:bg-gray-100 dark:file:bg-gray-600 file:text-gray-700 dark:file:text-gray-200">
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('sup.nuevoUsuarioForm') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-5 rounded-md hover:bg-gray-400 transition">
                            Regresar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-md transition">
                            Subir Documentos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
