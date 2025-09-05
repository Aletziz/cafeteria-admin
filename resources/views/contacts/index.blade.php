@extends('layout')

@section('title', 'Mensajes de Contacto')
@section('page-title', 'Gestión de Mensajes de Contacto')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Mensajes de Contacto</h1>
                    <p class="text-muted mb-0">Gestiona los mensajes recibidos desde el formulario de contacto</p>
                </div>
                @if($unreadCount > 0)
                    <span class="badge bg-danger fs-6">{{ $unreadCount }} sin leer</span>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    @if($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Estado</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Asunto</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts as $contact)
                                        <tr class="{{ !$contact->read ? 'table-warning' : '' }}">
                                            <td>
                                                @if($contact->read)
                                                    <span class="badge bg-success">Leído</span>
                                                @else
                                                    <span class="badge bg-warning">Sin leer</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $contact->name }}</strong>
                                                @if($contact->phone)
                                                    <br><small class="text-muted">{{ $contact->phone }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ $contact->subject }}</td>
                                            <td>
                                                <small>{{ $contact->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('contacts.show', $contact) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Ver
                                                    </a>
                                                    
                                                    <form action="{{ route('contacts.toggle-read', $contact) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-{{ $contact->read ? 'warning' : 'success' }}">
                                                            <i class="fas fa-{{ $contact->read ? 'envelope' : 'envelope-open' }}"></i>
                                                            {{ $contact->read ? 'Marcar sin leer' : 'Marcar leído' }}
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('contacts.destroy', $contact) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('¿Estás seguro de eliminar este mensaje?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i> Eliminar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $contacts->links('custom.pagination') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay mensajes de contacto</h5>
                            <p class="text-muted">Los mensajes enviados desde el formulario de contacto aparecerán aquí.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection