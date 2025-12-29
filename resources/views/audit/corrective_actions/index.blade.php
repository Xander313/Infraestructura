@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Acciones Correctivas</h1>
        <a href="{{ route('corrective_actions.create') }}" class="btn btn-success">Nueva Acción</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Hallazgo</th>
                <th>Propietario</th>
                <th>Fecha Límite</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($actions as $action)
            <tr>
                <td>{{ $action->ca_id }}</td>
                <td>{{ $action->finding->description }}</td>
                <td>{{ $action->owner->full_name ?? 'N/A' }}</td>
                <td>{{ $action->due_at }}</td>
                <td>
                    <span class="badge 
                        {{ $action->status == 'open' ? 'bg-info' : ($action->status == 'in_progress' ? 'bg-warning' : 'bg-success') }}">
                        {{ ucfirst(str_replace('_', ' ', $action->status)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('corrective_actions.show', $action->ca_id) }}" class="btn btn-sm btn-primary">Ver</a>
                    <a href="{{ route('corrective_actions.edit', $action->ca_id) }}" class="btn btn-sm btn-warning text-white">Editar</a>
                    <form action="{{ route('corrective_actions.destroy', $action->ca_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
