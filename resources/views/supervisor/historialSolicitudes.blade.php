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
        <h2 class="text-2xl font-bold mb-4">Historial de Solicitudes</h2>

        @if($solicitudes->isEmpty())
            <p>No has realizado ninguna solicitud aún.</p>
        @else
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">No.</th>
                        <th class="border px-4 py-2">Nombre</th>
                        <th class="border px-4 py-2">CURP</th>
                        <th class="border px-4 py-2">Fecha</th>
                        <th class="border px-4 py-2">Estado</th>
                        <th class="border px-4 py-2">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($solicitudes as $solicitud)
                        <tr>
                            <td class="border px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $solicitud->nombre }} {{ $solicitud->apellido_paterno }} {{ $solicitud->apellido_materno }}</td>
                            <td class="border px-4 py-2">{{ $solicitud->curp }}</td>
                            <td class="border px-4 py-2">{{ $solicitud->created_at->format('d/m/Y') }}</td>
                            <td class="border px-4 py-2">{{ $solicitud->status }}</td>
                            <td class="border px-4 py-2">
                                <a href="#" class="text-blue-500 hover:text-blue-700">Ver Más</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <center><br><a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                Regresar
            </a></center>

        @endif
    </div>
</x-app-layout>
