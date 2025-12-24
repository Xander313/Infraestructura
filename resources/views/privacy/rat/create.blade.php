@extends('layouts.app')

@section('title', 'Nuevo RAT')
@section('active_key', 'rat')

@section('content')
<div class="bg-white border rounded p-5">
    <h2 class="text-lg font-bold mb-4">Nueva Actividad de Tratamiento</h2>

    <form method="POST" action="{{ route('rat.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Nombre</label>
            <input type="text" name="name"
                class="w-full border rounded p-2"
                required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Categorías de datos</label>
            @foreach($categories as $cat)
            <label class="block">
                <input type="checkbox" name="data_categories[]"
                    value="{{ $cat->data_cat_id }}">
                {{ $cat->name }}
            </label>
            @endforeach
        </div>

        <button type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded">
            Guardar
        </button>
    </form>
</div>
@endsection