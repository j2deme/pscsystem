<x-app-layout>
    <x-navbar />

    <div class="py-8 px-6 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-white mb-6 text-center">Acuses de Bajas </h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-900 p-3 rounded mb-4">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="bg-red-100 text-red-900 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        @if ($bajas->count())
            <div class="overflow-x-auto rounded shadow">
                <table class="min-w-full text-sm text-left text-white bg-gray-800">
                    <thead class="bg-gray-700 text-xs uppercase text-gray-300">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Motivo</th>
                            <th class="px-6 py-3">Estatus</th>
                            <th class="px-6 py-3">Acuse</th>
                        </tr>
                    </thead>
                    <tbody class="text-white">
                        @foreach ($bajas as $baja)
                            <tr class="border-b border-gray-600">
                                <td class="px-6 py-4">{{ $baja->usuario->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $baja->motivo ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-green-600 text-white px-2 py-1 rounded text-xs">{{ $baja->estatus }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($baja->acuse)
                                        <a href="{{ Storage::url($baja->acuse->archivo) }}" class="text-blue-400 underline" target="_blank">Ver Acuse</a>
                                    @else
                                        <form action="{{ route('aux.acusesbajasupload', $baja->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                            @csrf
                                            <input type="file" name="archivo" accept="application/pdf" required class="text-white text-sm">
                                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">Subir Acuse</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center">
               {{ $bajas->links() }}

            </div>
        @else
            <p class="text-gray-400">No hay bajas aceptadas recientes.</p>
        @endif

        <div class="mt-6">
            <a href="{{ url()->previous() }}" class="bg-white border border-blue-600 text-blue-600 px-6 py-2 rounded hover:bg-blue-50">Regresar</a>
        </div>
    </div>
</x-app-layout>
