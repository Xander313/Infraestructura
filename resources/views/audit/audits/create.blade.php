@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($audit) ? 'Editar Auditoría' : 'Nueva Auditoría' }}</h1>

    <form action="{{ isset($audit) ? route('audits.update', $audit->audit_id) : route('audits.store') }}" method="POST">
        @csrf
        @if(isset($audit)) @method('PUT') @endif

        {{-- Tipo de Auditoría --}}
        <div class="mb-3">
            <label class="form-label">Tipo de Auditoría</label>
            <input
                type="text"
                class="form-control"
                name="audit_type"
                value="{{ old('audit_type', $audit->audit_type ?? '') }}"
                required
            >
        </div>

        {{-- Alcance --}}
        <div class="mb-3">
            <label class="form-label">Alcance</label>
            <textarea
                class="form-control"
                name="scope"
                rows="3"
            >{{ old('scope', $audit->scope ?? '') }}</textarea>
        </div>

        {{-- Auditor --}}
        <div class="mb-3">
            <label class="form-label">Auditor</label>
            <select class="form-select" name="auditor_user_id">
                <option value="">-- Seleccione --</option>
                @foreach($users as $user)
                    <option
                        value="{{ $user->user_id }}"
                        {{ old('auditor_user_id', $audit->auditor_user_id ?? '') == $user->user_id ? 'selected' : '' }}
                    >
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Fecha planeada --}}
        <div class="mb-3">
            <label class="form-label">Fecha Planeada</label>
            <input
                type="datetime-local"
                class="form-control"
                name="planned_at"
                value="{{ isset($audit) && $audit->planned_at ? date('Y-m-d\TH:i', strtotime($audit->planned_at)) : '' }}"
            >
        </div>

        {{-- Estado --}}
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="status" required>
                <option value="PLANNED" {{ (isset($audit) && $audit->status === 'PLANNED') ? 'selected' : '' }}>
                    Planeada
                </option>
                <option value="IN_PROGRESS" {{ (isset($audit) && $audit->status === 'IN_PROGRESS') ? 'selected' : '' }}>
                    En ejecución
                </option>
                <option value="CLOSED" {{ (isset($audit) && $audit->status === 'CLOSED') ? 'selected' : '' }}>
                    Cerrada
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ isset($audit) ? 'Actualizar' : 'Guardar' }}
        </button>

        <a href="{{ route('audits.index') }}" class="btn btn-secondary ms-2">
            Cancelar
        </a>
    </form>
</div>
@endsection