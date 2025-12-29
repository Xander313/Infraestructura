@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($audit) ? 'Editar Auditoría' : 'Nueva Auditoría' }}</h1>

    <form action="{{ isset($audit) ? route('audits.update', $audit->audit_id) : route('audits.store') }}" method="POST">
        @csrf
        @if(isset($audit)) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Organización</label>
            <select class="form-select" name="org_id" required>
                @foreach($orgs as $org)
                    <option value="{{ $org->org_id }}" {{ isset($audit) && $audit->org_id == $org->org_id ? 'selected' : '' }}>
                        {{ $org->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de Auditoría</label>
            <input type="text" class="form-control" name="audit_type" value="{{ $audit->audit_type ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alcance</label>
            <textarea class="form-control" name="scope" rows="3">{{ $audit->scope ?? '' }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Auditor</label>
            <select class="form-select" name="auditor_user_id">
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}" {{ isset($audit) && $audit->auditor_user_id == $user->user_id ? 'selected' : '' }}>
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha Planeada</label>
            <input type="datetime-local" class="form-control" name="planned_at" value="{{ isset($audit) && $audit->planned_at ? date('Y-m-d\TH:i', strtotime($audit->planned_at)) : '' }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="status">
                <option value="planned" {{ isset($audit) && $audit->status == 'planned' ? 'selected' : '' }}>Planeada</option>
                <option value="executed" {{ isset($audit) && $audit->status == 'executed' ? 'selected' : '' }}>Ejecutada</option>
                <option value="cancelled" {{ isset($audit) && $audit->status == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($audit) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('audits.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
