@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Auditorías</h1>
        <a href="{{ route('audits.create') }}" class="btn btn-success">Nueva Auditoría</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Organización</th>
                <th>Tipo</th>
                <th>Auditor</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $audit)
            <tr>
                <td>{{ $audit->audit_id }}</td>
                <td>{{ $audit->org->name }}</td>
                <td>{{ $audit->audit_type }}</td>
                <td>{{ $audit->auditor->full_name ?? 'N/A' }}</td>
                <td>
                    <span class="badge 
                        {{ $audit->status == 'planned' ? 'bg-info' : ($audit->status == 'executed' ? 'bg-success' : 'bg-danger') }}">
                        {{ ucfirst($audit->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('audits.show', $audit->audit_id) }}" class="btn btn-sm btn-primary">Ver</a>
                    <a href="{{ route('audits.edit', $audit->audit_id) }}" class="btn btn-sm btn-warning text-white">Editar</a>
                    <form action="{{ route('audits.destroy', $audit->audit_id) }}" method="POST" style="display:inline;">
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
