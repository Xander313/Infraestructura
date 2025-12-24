@extends('layouts.app')

@section('title', 'RAT')
@section('active_key', 'rat')

@section('page_header')


<div class="flex justify-between items-center">
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
    <div class="border-b py-2">
        {{ $a->name }}
    </div>
    @empty
    <p class="text-gray-500">No hay actividades registradas</p>
    @endforelse
</div>
@endsection