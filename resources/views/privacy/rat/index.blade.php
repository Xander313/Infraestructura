@extends('layouts.app')

@section('title', 'RAT')
@section('active_key', 'rat')

@section('page_header')
<div class="flex justify-between items-center mb-4">
    <div>
        <h2 class="text-xl font-bold">Actividades de Tratamiento</h2>
        <p class="text-sm text-gray-500">Registro RAT</p>
    </div>
    <a href="{{ route('rat.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded">
        Nuevo
    </a>
</div>
@endsection

@section('content')
<div class="bg-white border rounded p-4">
    @forelse($activities as $a)
    <div class="border-b py-2 flex justify-between items-center">
        <span>{{ $a->name }}</span>
        <div class="flex space-x-2">
            {{-- Botón Editar --}}
            <a href="{{ route('rat.edit', $a->pa_id) }}"
                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                Editar
            </a>

            {{-- Botón Eliminar --}}
            <form action="{{ route('rat.destroy', $a->pa_id) }}" method="POST" onsubmit="return confirm('¿Desea eliminar esta actividad?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                    Eliminar
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-gray-500">No hay actividades registradas</p>
    @endforelse
</div>
@endsection