@extends('layouts.app')

@section('title', 'Nueva Organización')
@section('active_key', 'org')

@section('content')
<div class="flex justify-center mt-10">
    <div class="w-full max-w-xl">

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 flex items-center gap-2">
                <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21V9h6v12"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Nueva Organización
                </h2>
            </div>

            {{-- Body --}}
            <div class="p-6">
                <form method="POST" action="{{ route('orgs.store') }}" class="space-y-5">
                    @csrf

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            required
                            placeholder="Nombre de la organización"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                        >
                    </div>

                    {{-- RUC --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            RUC
                        </label>
                        <input
                            type="text"
                            name="ruc"
                            placeholder="RUC (opcional)"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                        >
                    </div>

                    {{-- Industria --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Industria
                        </label>
                        <input
                            type="text"
                            name="industry"
                            placeholder="Industria o sector"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                        >
                    </div>

                    {{-- Acciones --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('orgs.index') }}"
                           class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm hover:bg-gray-50">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="px-5 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
