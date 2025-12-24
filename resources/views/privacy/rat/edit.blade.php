@extends('layouts.app')

@section('title', 'Editar RAT')
@section('active_key', 'rat')

@section('content')
<div class="bg-white border rounded p-5">
    <h2 class="text-lg font-bold mb-4">Editar Actividad de Tratamiento</h2>

    <form method="POST" action="{{ route('rat.update', $activity->pa_id) }}">
        @csrf
        @method('PUT')

        {{-- Tabs --}}
        <div x-data="{ tab: 'details' }">
            <div class="flex border-b mb-4">
                <button type="button" @click="tab='details'" :class="tab==='details'?'border-b-2 border-blue-500':''" class="px-4 py-2">Detalles</button>
                <button type="button" @click="tab='categories'" :class="tab==='categories'?'border-b-2 border-blue-500':''" class="px-4 py-2">Categorías</button>
                <button type="button" @click="tab='retention'" :class="tab==='retention'?'border-b-2 border-blue-500':''" class="px-4 py-2">Retención</button>
                <button type="button" @click="tab='transfers'" :class="tab==='transfers'?'border-b-2 border-blue-500':''" class="px-4 py-2">Transferencias</button>
            </div>

            {{-- Detalles --}}
            <div x-show="tab==='details'">
                <div class="mb-4">
                    <label class="block text-sm font-medium">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded p-2" value="{{ $activity->name }}" required>
                </div>
            </div>

            {{-- Categorías --}}
            <div x-show="tab==='categories'">
                @foreach($categories as $cat)
                @php
                // Determinar si la categoría está seleccionada
                $isChecked = in_array($cat->data_cat_id, $selectedCategories);

                // Obtener collection_source del pivot si existe
                $collectionSource = $categoryPivot[$cat->data_cat_id] ?? '';
                @endphp
                <div class="mb-2 border p-2 rounded flex items-center">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="data_categories[{{ $cat->data_cat_id }}][checked]"
                            value="1"
                            x-model="selected_{{ $cat->data_cat_id }}"
                            {{ $isChecked ? 'checked' : '' }}>
                        <span class="ml-2">{{ $cat->name }}</span>
                    </label>

                    <input
                        type="text"
                        name="data_categories[{{ $cat->data_cat_id }}][collection_source]"
                        placeholder="Fuente de colección"
                        class="border p-1 ml-4 flex-1"
                        x-bind:disabled="!selected_{{ $cat->data_cat_id }}"
                        value="{{ $collectionSource }}">
                </div>
                @endforeach
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    @foreach($categories as $cat)
                    Alpine.data('selected_{{ $cat->data_cat_id }}', () => ({
                        selected: {
                            {
                                in_array($cat - > data_cat_id, $selectedCategories) ? 'true' : 'false'
                            }
                        }
                    }));
                    @endforeach
                });
            </script>


            @endsection