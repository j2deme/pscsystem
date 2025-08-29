<x-app-layout>
    <x-navbar></x-navbar>
    <div class="py-8 px-4 sm:py-6 sm:px-6">
        <div class="container mx-auto max-w-4xl">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 rounded-r text-green-900 px-4 py-3 shadow-md mb-6" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 rounded-r text-red-900 px-4 py-3 shadow-md mb-6" role="alert">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Subir Archivos de Informes
                            </h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Cargue los archivos PDF y Excel de informes para PSC, SPyT y Montana
                            </p>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full">
                                <span class="text-sm font-medium">6</span>
                                <span class="text-xs">archivos</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('confrontas.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- PDF PSC -->
                        <div x-data="dropFile('inf_psc')"
                             x-on:dragover.prevent
                             x-on:drop.prevent="handleDrop($event)"
                             class="border-2 border-dashed border-blue-400 dark:border-blue-600 rounded-xl p-6 text-center hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md">

                            <div class="mb-4">
                                <div class="flex-shrink-0 h-12 w-12 mx-auto">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-900 dark:text-white font-medium mb-3">
                                PDF PSC
                            </label>

                            <input type="file"
                                   name="inf_psc"
                                   x-ref="fileInput"
                                   accept="application/pdf"
                                   class="hidden"
                                   @change="handleFileInput"
                                   required>

                            <button type="button"
                                    @click="$refs.fileInput.click()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Seleccionar archivo
                            </button>

                            <template x-if="fileName">
                                <div class="mt-3 p-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <p class="text-xs text-blue-600 dark:text-blue-400 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Archivo:</span>
                                        <span x-text="fileName" class="truncate ml-1"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- PDF SPyT -->
                        <div x-data="dropFile('inf_spyt')"
                             x-on:dragover.prevent
                             x-on:drop.prevent="handleDrop($event)"
                             class="border-2 border-dashed border-green-400 dark:border-green-600 rounded-xl p-6 text-center hover:border-green-500 dark:hover:border-green-500 transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md">

                            <div class="mb-4">
                                <div class="flex-shrink-0 h-12 w-12 mx-auto">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-900 dark:text-white font-medium mb-3">
                                PDF SPyT
                            </label>

                            <input type="file"
                                   name="inf_spyt"
                                   x-ref="fileInput"
                                   accept="application/pdf"
                                   class="hidden"
                                   @change="handleFileInput"
                                   required>

                            <button type="button"
                                    @click="$refs.fileInput.click()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Seleccionar archivo
                            </button>

                            <template x-if="fileName">
                                <div class="mt-3 p-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                    <p class="text-xs text-green-600 dark:text-green-400 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Archivo:</span>
                                        <span x-text="fileName" class="truncate ml-1"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- PDF Montana -->
                        <div x-data="dropFile('inf_montana')"
                             x-on:dragover.prevent
                             x-on:drop.prevent="handleDrop($event)"
                             class="border-2 border-dashed border-purple-400 dark:border-purple-600 rounded-xl p-6 text-center hover:border-purple-500 dark:hover:border-purple-500 transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md">

                            <div class="mb-4">
                                <div class="flex-shrink-0 h-12 w-12 mx-auto">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-900 dark:text-white font-medium mb-3">
                                PDF Montana
                            </label>

                            <input type="file"
                                   name="inf_montana"
                                   x-ref="fileInput"
                                   accept="application/pdf"
                                   class="hidden"
                                   @change="handleFileInput"
                                   required>

                            <button type="button"
                                    @click="$refs.fileInput.click()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Seleccionar archivo
                            </button>

                            <template x-if="fileName">
                                <div class="mt-3 p-2 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                                    <p class="text-xs text-purple-600 dark:text-purple-400 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Archivo:</span>
                                        <span x-text="fileName" class="truncate ml-1"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Excel PSC -->
                        <div x-data="dropFile('exc_psc')"
                             x-on:dragover.prevent
                             x-on:drop.prevent="handleDrop($event)"
                             class="border-2 border-dashed border-yellow-400 dark:border-yellow-600 rounded-xl p-6 text-center hover:border-yellow-500 dark:hover:border-yellow-500 transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md">

                            <div class="mb-4">
                                <div class="flex-shrink-0 h-12 w-12 mx-auto">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-900 dark:text-white font-medium mb-3">
                                Excel PSC
                            </label>

                            <input type="file"
                                   name="exc_psc"
                                   x-ref="fileInput"
                                   accept=".xlsx,.xls"
                                   class="hidden"
                                   @change="handleFileInput"
                                   required>

                            <button type="button"
                                    @click="$refs.fileInput.click()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Seleccionar archivo
                            </button>

                            <template x-if="fileName">
                                <div class="mt-3 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <p class="text-xs text-yellow-600 dark:text-yellow-400 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Archivo:</span>
                                        <span x-text="fileName" class="truncate ml-1"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Excel SPyT -->
                        <div x-data="dropFile('exc_spyt')"
                             x-on:dragover.prevent
                             x-on:drop.prevent="handleDrop($event)"
                             class="border-2 border-dashed border-orange-400 dark:border-orange-600 rounded-xl p-6 text-center hover:border-orange-500 dark:hover:border-orange-500 transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md">

                            <div class="mb-4">
                                <div class="flex-shrink-0 h-12 w-12 mx-auto">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-900 dark:text-white font-medium mb-3">
                                Excel SPyT
                            </label>

                            <input type="file"
                                   name="exc_spyt"
                                   x-ref="fileInput"
                                   accept=".xlsx,.xls"
                                   class="hidden"
                                   @change="handleFileInput"
                                   required>

                            <button type="button"
                                    @click="$refs.fileInput.click()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Seleccionar archivo
                            </button>

                            <template x-if="fileName">
                                <div class="mt-3 p-2 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg">
                                    <p class="text-xs text-orange-600 dark:text-orange-400 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Archivo:</span>
                                        <span x-text="fileName" class="truncate ml-1"></span>
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Excel Montana -->
                        <div x-data="dropFile('exc_montana')"
                             x-on:dragover.prevent
                             x-on:drop.prevent="handleDrop($event)"
                             class="border-2 border-dashed border-red-400 dark:border-red-600 rounded-xl p-6 text-center hover:border-red-500 dark:hover:border-red-500 transition-all duration-200 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md">

                            <div class="mb-4">
                                <div class="flex-shrink-0 h-12 w-12 mx-auto">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <label class="block text-gray-900 dark:text-white font-medium mb-3">
                                Excel Montana
                            </label>

                            <input type="file"
                                   name="exc_montana"
                                   x-ref="fileInput"
                                   accept=".xlsx,.xls"
                                   class="hidden"
                                   @change="handleFileInput"
                                   required>

                            <button type="button"
                                    @click="$refs.fileInput.click()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Seleccionar archivo
                            </button>

                            <template x-if="fileName">
                                <div class="mt-3 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                    <p class="text-xs text-red-600 dark:text-red-400 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="font-medium">Archivo:</span>
                                        <span x-text="fileName" class="truncate ml-1"></span>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <button type="submit"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Guardar Deducción
                            </button>
                            <a href="{{ route('dashboard') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-200 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function dropFile(inputName) {
        return {
            fileName: null,
            handleDrop(event) {
                const file = event.dataTransfer.files[0];
                if (file) {
                    const allowedTypes = ['application/pdf', '.xlsx', '.xls'];
                    const fileType = file.type;
                    const fileName = file.name.toLowerCase();

                    if (fileType === 'application/pdf' || fileName.endsWith('.xlsx') || fileName.endsWith('.xls')) {
                        this.$refs.fileInput.files = event.dataTransfer.files;
                        this.fileName = file.name;
                    } else {
                        alert('Por favor, seleccione un archivo PDF o Excel válido.');
                    }
                }
            },
            handleFileInput(event) {
                const file = event.target.files[0];
                if (file) {
                    const allowedTypes = ['application/pdf', '.xlsx', '.xls'];
                    const fileType = file.type;
                    const fileName = file.name.toLowerCase();

                    if (fileType === 'application/pdf' || fileName.endsWith('.xlsx') || fileName.endsWith('.xls')) {
                        this.fileName = file.name;
                    } else {
                        alert('Por favor, seleccione un archivo PDF o Excel válido.');
                        this.$refs.fileInput.value = '';
                    }
                } else {
                    this.fileName = null;
                }
            }
        }
    }
</script>
