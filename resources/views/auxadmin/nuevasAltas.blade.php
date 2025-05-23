<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Nuevas Altas</h1>
                @if($solicitudes->isEmpty())
            <p class="mt-4">No hay nuevas solicitudes y/o pendientes de subida de archivos.</p>
        @else
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CURP</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">RFC</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha de solicitud</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($solicitudes as $solicitud)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{$loop->iteration }}</td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ trim(($solicitud->nombre ?? '') . ' ' . ($solicitud->apellido_paterno ?? '') . ' ' . ($solicitud->apellido_materno ?? '')) ?: 'N/D' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $solicitud->curp ?? 'N/D' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $solicitud->rfc ?? 'N/D' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ optional($solicitud->created_at)->format('d/m/Y') ?? 'Sin fecha' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $status = $solicitud->status ?? 'Desconocido';
                                        $statusClasses = match($status) {
                                            'En Proceso' => 'bg-yellow-100 text-yellow-800',
                                            'Aceptada'   => 'bg-green-100 text-green-800',
                                            'Rechazada'  => 'bg-red-100 text-red-800',
                                            default      => 'bg-gray-200 text-gray-800',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-blue-600 hover:text-blue-900" onclick="abrirModalCarga({{ $solicitud->id }})">Acciones</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
            <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                Regresar
            </a></center>
        </div>
    </div>
</x-app-layout>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function abrirModalCarga(solicitudId) {
        Swal.fire({
    title: 'Subir Documentos',
    html: `
        <div class="grid grid-cols-2 gap-4 text-left">
            <div id="drop-imss" class="border-dashed border-2 border-blue-400 rounded-md p-4">
                <label class="block text-gray-700 font-semibold mb-2">Acuse IMSS</label>
                <input type="file" id="file-imss" hidden>
                <button type="button"
                        onclick="document.getElementById('file-imss').click()"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Seleccionar archivo
                </button>
                <p id="file-name-imss" class="mt-2 text-sm text-green-600"></p>
            </div>

            <div id="drop-infonavit" class="border-dashed border-2 border-blue-400 rounded-md p-4">
                <label class="block text-gray-700 font-semibold mb-2">Retención INFONAVIT</label>
                <input type="file" id="file-infonavit" hidden>
                <button type="button"
                        onclick="document.getElementById('file-infonavit').click()"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Seleccionar archivo
                </button>
                <p id="file-name-infonavit" class="mt-2 text-sm text-green-600"></p>
            </div>
        </div>

        <div class="mt-6">
            <label class="block text-gray-700 font-semibold mb-1">SD</label>
            <input type="number" id="input-sd" step="0.01" min="0" class="w-full border border-gray-300 rounded-md p-2">

            <label class="block text-gray-700 font-semibold mt-4 mb-1">SDI</label>
            <input type="number" id="input-sdi" step="0.01" min="0" class="w-full border border-gray-300 rounded-md p-2">
        </div>
    `,
    showCancelButton: true,
    confirmButtonText: 'Subir',
    cancelButtonText: 'Cancelar',
    didOpen: () => {
        const zonas = [
            { zona: 'drop-imss', input: 'file-imss', label: 'file-name-imss' },
            { zona: 'drop-infonavit', input: 'file-infonavit', label: 'file-name-infonavit' }
        ];

        zonas.forEach(({ zona, input, label }) => {
            const dropZone = document.getElementById(zona);
            const fileInput = document.getElementById(input);
            const fileLabel = document.getElementById(label);

            dropZone.addEventListener('dragover', e => e.preventDefault());
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                const file = e.dataTransfer.files[0];
                if (!file) return;

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                fileLabel.textContent = 'Archivo: ' + file.name;
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files[0]) {
                    fileLabel.textContent = 'Archivo: ' + fileInput.files[0].name;
                }
            });
        });
    },
    preConfirm: () => {
        const fileImss = document.getElementById('file-imss').files[0];
        const fileInfonavit = document.getElementById('file-infonavit').files[0];
        const sd = document.getElementById('input-sd').value;
        const sdi = document.getElementById('input-sdi').value;

        if (!fileImss) {
            Swal.showValidationMessage('Debes seleccionar el archivo de Acuse IMSS');
            return false;
        }

        const formData = new FormData();
        formData.append('solicitud_id', solicitudId);
        formData.append('arch_acuse_imss', fileImss);
        if (fileInfonavit) {
            formData.append('arch_retencion_infonavit', fileInfonavit);
        }
        formData.append('sd', sd);
        formData.append('sdi', sdi);

        return fetch(`/subida_documentacion/${solicitudId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al subir archivos');
            return response.json();
        })
        .catch(error => {
            Swal.showValidationMessage(`Error: ${error.message}`);
        });
    }
});

    }
</script>

