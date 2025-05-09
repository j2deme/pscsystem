<div>
    <div class="mb-6 justify-between">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Buscar por nombre o fecha"
            class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        >
        <input
            type="date"
            wire:model.live="fecha"
            class="w-1/3 p-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
        />
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
                        Elemento
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Punto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Fecha
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Asunto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($quejas as $queja)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $loop->iteration }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $queja->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $queja->user->punto }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ \Carbon\Carbon::parse($queja->fecha)->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-500 dark:text-gray-300">
                        {{ $queja->asunto }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm ">
                        <a href="#"
                            onclick="mostrarDetalles('{{ addslashes($queja->mensaje) }}')"
                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500 mr-3">
                            Ver Detalles
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$quejas->links()}}
        <center>
            <a href="{{ route('dashboard') }}" class="inline-block bg-gray-300 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-400 mr-2">
                Regresar
            </a>
        </center>
    </div>
</div>
<script>
    function mostrarDetalles(mensaje) {
        Swal.fire({
            title: 'Detalles de la Queja/Sugerencia',
            input: 'textarea',
            inputValue: mensaje,
            inputAttributes: {
                readonly: true,
                style: 'min-height: 150px; resize: none;'
            },
            showCancelButton: false,
            confirmButtonText: 'Cerrar',
            customClass: {
                popup: 'rounded-lg'
            }
        });
    }
</script>

