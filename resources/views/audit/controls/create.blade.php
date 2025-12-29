@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($control) ? 'Editar Control' : 'Nuevo Control' }}</h1>

    <form action="{{ isset($control) ? route('controls.update', $control->control_id) : route('controls.store') }}" method="POST">
        @csrf
        @if(isset($control)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Organización</label>
            <select class="form-select" name="org_id" required>
                @foreach($orgs as $org)
                    <option value="{{ $org->org_id }}" {{ isset($control) && $control->org_id == $org->org_id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Código</label>
            <input type="text" class="form-control" name="code" value="{{ $control->code ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" name="name" value="{{ $control->name ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <input type="text" class="form-control" name="control_type" value="{{ $control->control_type ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="description" rows="3">{{ $control->description ?? '' }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Propietario</label>
            <select class="form-select" name="owner_user_id">
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}" {{ isset($control) && $control->owner_user_id == $user->user_id ? 'selected' : '' }}>
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($control) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('controls.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
