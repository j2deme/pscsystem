<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl text-gray-800 dark:text-white mb-4">
                    Vacaciones
                </h1>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No.</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Días Solicitados</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Inicio</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Fin</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($solicitudes as $index => $solicitud)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $solicitudes->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $solicitud->dias_solicitados }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_fin)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    @if ($solicitud->estatus == 'En Proceso')
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-yellow-100 bg-yellow-500 rounded-full">
                                            {{ $solicitud->estatus }}
                                        </span>
                                    @elseif ($solicitud->estatus == 'Aceptada')
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-green-100 bg-green-600 rounded-full">
                                            {{ $solicitud->estatus }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-red-100 bg-red-600 rounded-full">
                                            {{ $solicitud->estatus }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500">
                                    <a href="#" onclick="abrirModalArchivo({{ $solicitud->id }})" class="ml-2 text-green-700 hover:text-green-800">Subir Archivo</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-sm text-gray-500 dark:text-gray-300 py-4">
                                    No hay solicitudes pendientes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $solicitudes->links() }}
                </div>

                <center class="mt-6">
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a>
                </center>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    function abrirModalArchivo(solicitudId) {
        Swal.fire({
            title: 'Subir Archivo',
            html: `
                <input type="file" id="archivo" class="swal2-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <p style="font-size:12px; color:#888">Formatos permitidos: PDF, DOC, JPG, PNG (Máx. 5MB)</p>
                <div id="progress-container" style="margin-top:10px; display:none;">
                    <progress id="upload-progress" value="0" max="100" style="width:100%"></progress>
                    <p id="progress-text" style="text-align:center; font-size:12px;">0% completado</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Subir',
            cancelButtonText: 'Cancelar',
            didOpen: () => {
                const fileInput = document.getElementById('archivo');
                fileInput.addEventListener('change', (e) => {
                    if(e.target.files[0].size > 5 * 1024 * 1024) {
                        Swal.showValidationMessage('El archivo no puede exceder los 5MB');
                    }
                });
            },
            preConfirm: () => {
                const archivo = document.getElementById('archivo').files[0];
                if (!archivo) {
                    Swal.showValidationMessage('Debes seleccionar un archivo');
                    return false;
                }

                const formData = new FormData();
                formData.append('archivo', archivo);
                formData.append('_token', '{{ csrf_token() }}');

                document.getElementById('progress-container').style.display = 'block';
                const progressBar = document.getElementById('upload-progress');
                const progressText = document.getElementById('progress-text');

                return fetch(`/solicitud-vacaciones/${solicitudId}/subir-archivo`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw new Error(err.message || 'Error al subir el archivo') });
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    return data;
                })
                .catch(error => {
                    Swal.showValidationMessage(`Error: ${error.message}`);
                    return false;
                });
            }
        }).then(result => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: result.value.message,
                    icon: 'success'
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }
</script>
