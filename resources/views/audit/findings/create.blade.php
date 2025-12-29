@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($finding) ? 'Editar Hallazgo' : 'Nuevo Hallazgo' }}</h1>

    <form action="{{ isset($finding) ? route('findings.update', $finding->finding_id) : route('findings.store') }}" method="POST">
        @csrf
        @if(isset($finding)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Auditoría</label>
            <select class="form-select" name="audit_id" required>
                @foreach($audits as $audit)
                    <option value="{{ $audit->audit_id }}" {{ isset($finding) && $finding->audit_id == $audit->audit_id ? 'selected' : '' }}>
                        {{ $audit->audit_type }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Control</label>
            <select class="form-select" name="control_id">
                <option value="">--Ninguno--</option>
                @foreach($controls as $control)
                    <option value="{{ $control->control_id }}" {{ isset($finding) && $finding->control_id == $control->control_id ? 'selected' : '' }}>
                        {{ $control->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Severidad</label>
            <input type="text" class="form-control" name="severity" value="{{ $finding->severity ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="status">
                <option value="open" {{ isset($finding) && $finding->status == 'open' ? 'selected' : '' }}>Abierto</option>
                <option value="in_progress" {{ isset($finding) && $finding->status == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                <option value="closed" {{ isset($finding) && $finding->status == 'closed' ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea class="form-control" name="description" rows="3">{{ $finding->description ?? '' }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($finding) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('findings.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
