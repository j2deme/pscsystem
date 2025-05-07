<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
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
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Solicitudes de vacaciones</h1>
                @if($solicitudes->isEmpty())
                    <p class="text-gray-500">No hay solicitudes registradas.</p>
                    <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                        Regresar
                    </a></center>
                @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Empleado</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Inicio</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Días</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estatus</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($solicitudes as $solicitud)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-2">
                                                {{ $solicitud->user->name }}<br>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_inicio)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($solicitud->fecha_fin)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $solicitud->dias_solicitados }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    @if($solicitud->estatus === 'Aceptada')
                                                        bg-green-100 text-green-800
                                                    @elseif($solicitud->estatus === 'Rechazada')
                                                        bg-red-100 text-red-800
                                                    @else
                                                        bg-yellow-100 text-yellow-800
                                                    @endif
                                                ">
                                                    {{ $solicitud->estatus }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                @if($solicitud->estatus === 'En Proceso' && $solicitud->observaciones === 'Solicitud aceptada, falta subir archivo de solicitud.')
                                                    <a href="{{ route('sup.descargarSolicitudVacaciones', $solicitud->id) }}" class="text-blue-500 hover:text-blue-700">Descargar Formato</a>
                                                    <a href="#" onclick="abrirModalArchivo({{ $solicitud->id }})" class="ml-2 text-green-700 hover:text-green-800">Subir Archivo</a>
                                                @elseif ($solicitud->estatus === 'En Proceso' && $solicitud->observaciones === 'Solicitud de vacaciones en proceso')
                                                    <a href="{{ route('sup.aceptarSolicitudVacaciones', $solicitud->id) }}" class="text-blue-500 hover:text-blue-700">Aceptar</a>
                                                    <a href="{{ route('sup.rechazarSolicitudVacaciones', $solicitud->id) }}" class="ml-2 text-red-500 hover:text-red-700">Rechazar</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <p>Nota: Al aceptar una solicitud, se deberá descargae el archivo PDF con el formato de solicitud de vacaciones, el cual deberá ser firmado por el elemento, para después ser subido nuevamente al sistema y que la solicitud sea aceptada correctamente.</p>
                            <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2 mb-2">
                                Regresar
                            </a></center>
                        </div>
                @endif
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
