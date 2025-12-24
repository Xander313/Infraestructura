@extends('layouts.app')

@section('title', 'Nuevo RAT')
@section('active_key', 'rat')

@section('content')
<div class="bg-white border rounded p-5">
    <h2 class="text-lg font-bold mb-4">Nueva Actividad de Tratamiento</h2>

    <form method="POST" action="{{ route('rat.store') }}">
        @csrf

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
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>
            </div>

            {{-- Categorías --}}
            <div x-show="tab==='categories'">
                @foreach($categories as $cat)
                <label class="block">
                    <input type="checkbox" name="data_categories[]" value="{{ $cat->data_cat_id }}">
                    {{ $cat->name }}
                </label>
                @endforeach
            </div>

            {{-- Retención --}}
            <div x-show="tab==='retention'">
                <label class="block text-sm font-medium">Reglas de Retención (ejemplo)</label>
                <div>
                    <input type="number" name="retention_rules[0][retention_period_days]" placeholder="Días">
                    <input type="text" name="retention_rules[0][trigger_event]" placeholder="Evento">
                </div>
            </div>

            {{-- Transferencias --}}
            <div x-show="tab==='transfers'">
                <label class="block text-sm font-medium">Transferencias (ejemplo)</label>
                <div>
                    <!--
                    <select name="transfers[0][recipient_id]">
                        <option value="1">Proveedor 1</option>
                    </select>
                    <select name="transfers[0][country_id]">
                        <option value="1">País 1</option>
                    </select>-->

                    <select name="transfers[0][recipient_id]" class="border p-2 rounded">
                        @foreach($recipients as $recipient)
                        <option value="{{ $recipient->recipient_id }}">{{ $recipient->name }}</option>
                        @endforeach
                    </select>

                    <select name="transfers[0][country_id]" class="border p-2 rounded">
                        @foreach($countries as $country)
                        <option value="{{ $country->country_id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>

                </div>
            </div>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mt-4">
            Guardar
        </button>
    </form>
</div>

@endsection