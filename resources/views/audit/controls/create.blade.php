@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">
        {{ isset($control) ? 'Editar Control' : 'Nuevo Control' }}
    </h1>

    <form action="{{ isset($control) 
        ? route('controls.update', $control->control_id) 
        : route('controls.store') }}" 
        method="POST">

        @csrf
        @if(isset($control))
            @method('PUT')
        @endif

        {{-- Organización (solo informativa, no editable) --}}
        <div class="mb-3">
            <label class="form-label">Organización activa</label>
            <input 
                type="text" 
                class="form-control" 
                value="{{ session('org_id') }}" 
                disabled
            >
        </div>

        {{-- Código --}}
        <div class="mb-3">
            <label class="form-label">Código</label>
            <input 
                type="text" 
                class="form-control" 
                name="code" 
                value="{{ old('code', $control->code ?? '') }}" 
                required
            >
        </div>

        {{-- Nombre --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input 
                type="text" 
                class="form-control" 
                name="name" 
                value="{{ old('name', $control->name ?? '') }}" 
                required
            >
        </div>

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <input 
                type="text" 
                class="form-control" 
                name="control_type" 
                value="{{ old('control_type', $control->control_type ?? '') }}" 
                required
            >
        </div>

        {{-- Descripción --}}
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea 
                class="form-control" 
                name="description" 
                rows="3"
            >{{ old('description', $control->description ?? '') }}</textarea>
        </div>

        {{-- Propietario --}}
        <div class="mb-3">
            <label class="form-label">Propietario</label>
            <select class="form-select" name="owner_user_id">
                <option value="">-- Seleccione --</option>
                @foreach($users as $user)
                    <option 
                        value="{{ $user->user_id }}"
                        {{ old('owner_user_id', $control->owner_user_id ?? '') == $user->user_id ? 'selected' : '' }}
                    >
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Botones --}}
        <button type="submit" class="btn btn-primary">
            {{ isset($control) ? 'Actualizar' : 'Guardar' }}
        </button>

        <a href="{{ route('controls.index') }}" class="btn btn-secondary ms-2">
            Cancelar
        </a>
    </form>
</div>
@endsection
