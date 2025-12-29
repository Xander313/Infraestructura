@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Hallazgos</h1>
        <a href="{{ route('findings.create') }}" class="btn btn-success">Nuevo Hallazgo</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Auditoría</th>
                <th>Control</th>
                <th>Severidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($findings as $finding)
            <tr>
                <td>{{ $finding->finding_id }}</td>
                <td>{{ $finding->audit->audit_type }}</td>
                <td>{{ $finding->control->name ?? 'N/A' }}</td>
                <td>{{ $finding->severity }}</td>
                <td>
                    <span class="badge 
                        {{ $finding->status == 'open' ? 'bg-info' : ($finding->status == 'in_progress' ? 'bg-warning' : 'bg-success') }}">
                        {{ ucfirst(str_replace('_', ' ', $finding->status)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('findings.show', $finding->finding_id) }}" class="btn btn-sm btn-primary">Ver</a>
                    <a href="{{ route('findings.edit', $finding->finding_id) }}" class="btn btn-sm btn-warning text-white">Editar</a>
                    <form action="{{ route('findings.destroy', $finding->finding_id) }}" method="POST" style="display:inline;">
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
