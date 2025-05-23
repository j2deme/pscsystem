@php
    $currentPage = $users->currentPage();
    $lastPage = $users->lastPage();
@endphp



<div>
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre..."
            class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        >
        <div wire:loading class="text-sm text-gray-500 mt-1">
            Buscando...
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        No.
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Nombre
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Punto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Rol
                    </th>

                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Estatus
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                            {{ $user->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $user->solicitudAlta?->punto ?? 'No Disponible' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        @if($user->rol == 'admin')
                            Administrador
                        @else
                            {{ $user->rol }}
                        @endif
                        </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        @if($user->estatus == 'Activo')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $user->estatus }}
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $user->estatus }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="#"
                            onclick="abrirModalCarga({{ $user->sol_docs_id }}, {
                                nombre: '{{ $user->name }}',
                                sd: '{{ $user->solicitudAlta->sd ?? '' }}',
                                sdi: '{{ $user->solicitudAlta->sdi ?? '' }}',
                                imssNombre: '{{ optional($user->documentacionAltas)->arch_acuse_imss ? basename($user->documentacionAltas->arch_acuse_imss) : '' }}',
                                infonavitNombre: '{{ optional($user->documentacionAltas)->arch_retencion_infonavit ? basename($user->documentacionAltas->arch_retencion_infonavit) : '' }}',
                                imssUrl: '{{ optional($user->documentacionAltas)->arch_acuse_imss ? asset($user->documentacionAltas->arch_acuse_imss) : '' }}',
                                infonavitUrl: '{{ optional($user->documentacionAltas)->arch_retencion_infonavit ? asset($user->documentacionAltas->arch_retencion_infonavit) : '' }}'
                            })"
                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 mr-3">
                            Editar
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <ul class="flex justify-center space-x-2">
            @if ($users->onFirstPage())
                <li class="px-3 py-1 text-gray-500" aria-disabled="true">&laquo;</li>
            @else
                <li>
                    <button wire:click="previousPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&laquo;</button>
                </li>
            @endif

            @if ($currentPage > 2)
                <li>
                    <button wire:click="gotoPage(1)" class="px-3 py-1 text-blue-600 hover:text-blue-800">1</button>
                </li>
                @if ($currentPage > 3)
                    <li class="px-3 py-1 text-gray-500">...</li>
                @endif
            @endif

            @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                <li>
                    @if ($i == $currentPage)
                        <span class="px-3 py-1 bg-blue-500 text-white rounded">{{ $i }}</span>
                    @else
                        <button wire:click="gotoPage({{ $i }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">{{ $i }}</button>
                    @endif
                </li>
            @endfor

            @if ($currentPage < $lastPage - 1)
                @if ($currentPage < $lastPage - 2)
                    <li class="px-3 py-1 text-gray-500">...</li>
                @endif
                <li>
                    <button wire:click="gotoPage({{ $lastPage }})" class="px-3 py-1 text-blue-600 hover:text-blue-800">{{ $lastPage }}</button>
                </li>
            @endif

            @if ($users->hasMorePages())
                <li>
                    <button wire:click="nextPage" class="px-3 py-1 text-blue-600 hover:text-blue-800">&raquo;</button>
                </li>
            @else
                <li class="px-3 py-1 text-gray-500" aria-disabled="true">&raquo;</li>
            @endif
        </ul>

    </div><br>
        <center>
            @if(Auth::user()->rol == 'admin')
            <a href="{{ route('rh.generarNuevaAltaForm') }}" class="inline-block bg-blue-300 text-gray-800 py-2 px-4 rounded-md hover:bg-blue-400 mr-2">
                Nuevo Usuario
            </a>
            @endif
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                Regresar
            </a></center>
</div>
<script>
function abrirModalCarga(solicitudId, datos = {}) {
    Swal.fire({
        title: 'Subir Documentos de ' + (datos.nombre || ''),
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
                    <p id="file-name-imss" class="mt-2 text-sm text-green-600">
                        ${datos.imssNombre ? `Archivo actual: <a href="${datos.imssUrl}" target="_blank" class="underline text-blue-600">${datos.imssNombre}</a>` : ''}
                    </p>
                </div>

                <div id="drop-infonavit" class="border-dashed border-2 border-blue-400 rounded-md p-4">
                    <label class="block text-gray-700 font-semibold mb-2">Retención INFONAVIT</label>
                    <input type="file" id="file-infonavit" hidden>
                    <button type="button"
                            onclick="document.getElementById('file-infonavit').click()"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Seleccionar archivo
                    </button>
                    <p id="file-name-infonavit" class="mt-2 text-sm text-green-600">
                        ${datos.infonavitNombre ? `Archivo actual: <a href="${datos.infonavitUrl}" target="_blank" class="underline text-blue-600">${datos.infonavitNombre}</a>` : ''}
                    </p>
                </div>
                <div class="col-span-2 grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="input-sd" class="block text-gray-700 font-semibold mb-1">SD</label>
                        <input type="number" step="0.01" id="input-sd" value="${datos.sd || ''}"
                            class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="input-sdi" class="block text-gray-700 font-semibold mb-1">SDI</label>
                        <input type="number" step="0.01" id="input-sdi" value="${datos.sdi || ''}"
                            class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
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

                    fileLabel.innerHTML = 'Nuevo archivo: <span class="text-green-600">' + file.name + '</span>';
                });

                fileInput.addEventListener('change', () => {
                    if (fileInput.files[0]) {
                        fileLabel.innerHTML = 'Nuevo archivo: <span class="text-green-600">' + fileInput.files[0].name + '</span>';
                    }
                });
            });
        },
        preConfirm: () => {
            const fileImss = document.getElementById('file-imss').files[0];
            const fileInfonavit = document.getElementById('file-infonavit').files[0];

            if (!fileImss && !fileInfonavit) {
                Swal.showValidationMessage('Debes seleccionar al menos un archivo para subir');
                return false;
            }

            const formData = new FormData();
            formData.append('solicitud_id', solicitudId);
            if (fileImss) formData.append('arch_acuse_imss', fileImss);
            if (fileInfonavit) formData.append('arch_retencion_infonavit', fileInfonavit);

            return fetch(`/actualizacion_documentacion/${solicitudId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire('¡Listo!', 'Los archivos se subieron correctamente', 'success')
                .then(() => location.reload());
        }
    });
}

</script>
