<x-app-layout>
    <x-navbar></x-navbar>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-start gap-6 max-w-5xl mx-auto">

                    <div class="w-full md:w-2/3">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <div class="w-full md:w-1/3 flex justify-center md:justify-end">
                        <div class="text-center">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                Foto de perfil
                            </h2>

                            @php
                                $foto = auth()->user()->documentacionAltas?->arch_foto
                                    ? asset(auth()->user()->documentacionAltas->arch_foto)
                                    : asset('images/default-user.jpg');
                            @endphp

                            <img src="{{ $foto }}"
                                alt="Foto del usuario"
                                class="h-32 w-32 md:h-40 md:w-40 object-cover rounded-full border shadow">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex flex-col md:flex-row items-start gap-6 max-w-5xl mx-auto">
                    <div class="w-full md:w-2/3">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
