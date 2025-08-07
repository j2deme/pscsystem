<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-4 px-2 sm:py-6 sm:px-4">
        <div class="container mx-auto max-w-7xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Nuevas Altas</h1>
                @if($solicitudes->isEmpty())
                    <p class="mt-4">No hay nuevas altas registradas.</p>
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
                                            <a href="{{route('user.verFicha', $solicitud->user->id)}}" class="text-blue-600 hover:text-blue-900">Ver Más</a>
                                            <br><a href="#" class="text-indigo-600 hover:text-indigo-900"
                                                onclick="asignarNumeroEmpleado({{ $solicitud->user->id }}, '{{ $solicitud->nombre }} {{ $solicitud->apellido_paterno }}')">
                                                Asignar Núm. Empleado
                                            </a>
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
    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function asignarNumeroEmpleado(userId, nombreCompleto) {
        Swal.fire({
            title: 'Asignar Número de Empleado',
            text: `Asignar número a: ${nombreCompleto}`,
            input: 'number',
            inputAttributes: {
                min: 1
            },
            inputLabel: 'Número de empleado',
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            inputValidator: (value) => {
                if (!value) {
                    return 'Debe ingresar un número';
                }
                if (isNaN(value) || value < 1) {
                    return 'Debe ser un número válido';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('nominas.asignarNumeroEmpleado') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        num_empleado: result.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Guardado!', data.message, 'success')
                            .then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Ocurrió un error al guardar.', 'error');
                });
            }
        });
    }
</script>
@endpush
</x-app-layout>
