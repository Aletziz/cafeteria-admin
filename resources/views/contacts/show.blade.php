@extends('layout')

@section('title', 'Ver Mensaje de Contacto')
@section('page-title', 'Detalle del Mensaje de Contacto')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">Mensaje de Contacto</h1>
                    <p class="text-muted mb-0">Detalles del mensaje recibido</p>
                </div>
                <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $contact->subject }}</h5>
                            @if($contact->read)
                                <span class="badge bg-success">Leído</span>
                            @else
                                <span class="badge bg-warning">Sin leer</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Nombre</h6>
                                    <p class="mb-0">{{ $contact->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                    </p>
                                </div>
                            </div>
                            
                            @if($contact->phone)
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">Teléfono</h6>
                                        <p class="mb-0">
                                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">Fecha de envío</h6>
                                        <p class="mb-0">{{ $contact->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">Fecha de envío</h6>
                                        <p class="mb-0">{{ $contact->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                            @endif
                            
                            <hr>
                            
                            <h6 class="text-muted mb-3">Mensaje</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $contact->message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Acciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <form action="{{ route('contacts.toggle-read', $contact) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $contact->read ? 'warning' : 'success' }} w-100">
                                        <i class="fas fa-{{ $contact->read ? 'envelope' : 'envelope-open' }}"></i>
                                        {{ $contact->read ? 'Marcar como sin leer' : 'Marcar como leído' }}
                                    </button>
                                </form>
                                
                                <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" 
                                   class="btn btn-primary w-100">
                                    <i class="fas fa-reply"></i> Responder por email
                                </a>
                                
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" class="btn btn-info w-100">
                                        <i class="fas fa-phone"></i> Llamar
                                    </a>
                                @endif
                                
                                <hr>
                                
                                <form action="{{ route('contacts.destroy', $contact) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este mensaje? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Eliminar mensaje
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Información adicional</h6>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">
                                <strong>ID:</strong> {{ $contact->id }}<br>
                                <strong>Recibido:</strong> {{ $contact->created_at->diffForHumans() }}<br>
                                @if($contact->updated_at != $contact->created_at)
                                    <strong>Última actualización:</strong> {{ $contact->updated_at->diffForHumans() }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection