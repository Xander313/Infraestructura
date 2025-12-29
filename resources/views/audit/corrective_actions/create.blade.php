@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ isset($action) ? 'Editar Acción Correctiva' : 'Nueva Acción Correctiva' }}</h1>

    <form action="{{ isset($action) ? route('corrective_actions.update', ['corrective_action' => $action->ca_id]) : route('corrective_actions.store') }}" method="POST">
        @csrf
        @if(isset($action)) 
            @method('PUT') 
        @endif

        <!-- Hallazgo -->
        <div class="mb-3">
            <label class="form-label">Hallazgo</label>
            <select class="form-select" name="finding_id" required>
                @foreach($findings as $finding)
                    <option value="{{ $finding->finding_id }}" {{ isset($action) && $action->finding_id == $finding->finding_id ? 'selected' : '' }}>
                        {{ $finding->description }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Propietario -->
        <div class="mb-3">
            <label class="form-label">Propietario</label>
            <select class="form-select" name="owner_user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->user_id }}" {{ isset($action) && $action->owner_user_id == $user->user_id ? 'selected' : '' }}>
                        {{ $user->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Fecha Límite -->
        <div class="mb-3">
            <label class="form-label">Fecha Límite</label>
            <input type="datetime-local" class="form-control" name="due_at" 
                   value="{{ isset($action) && $action->due_at ? date('Y-m-d\TH:i', strtotime($action->due_at)) : '' }}">
        </div>

        <!-- Estado -->
        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select class="form-select" name="status">
                <option value="open" {{ isset($action) && $action->status == 'open' ? 'selected' : '' }}>Abierto</option>
                <option value="in_progress" {{ isset($action) && $action->status == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                <option value="closed" {{ isset($action) && $action->status == 'closed' ? 'selected' : '' }}>Cerrado</option>
            </select>
        </div>

        <!-- Fecha Cierre -->
        <div class="mb-3">
            <label class="form-label">Fecha Cierre</label>
            <input type="datetime-local" class="form-control" name="closed_at" 
                   value="{{ isset($action) && $action->closed_at ? date('Y-m-d\TH:i', strtotime($action->closed_at)) : '' }}">
        </div>

        <!-- Resultado -->
        <div class="mb-3">
            <label class="form-label">Resultado</label>
            <textarea class="form-control" name="outcome" rows="3">{{ $action->outcome ?? '' }}</textarea>
        </div>

        <!-- Botones -->
        <button type="submit" class="btn btn-primary">{{ isset($action) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('corrective_actions.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection
