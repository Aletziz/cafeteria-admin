@extends('layout')

@section('page-title', 'Gestión de Slides del Carrusel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Slides del Carrusel</h3>
                    <a href="{{ route('carousel-slides.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Slide
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($slides->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Título</th>
                                        <th>Imagen</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($slides as $slide)
                                        <tr>
                                            <td>{{ $slide->order }}</td>
                                            <td>{{ $slide->title }}</td>
                                            <td>
                                                <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" 
                                                     class="img-thumbnail" style="width: 80px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>
                                                @if($slide->is_active)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('carousel-slides.show', $slide) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('carousel-slides.edit', $slide) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('carousel-slides.destroy', $slide) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este slide?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay slides creados</h5>
                            <p class="text-muted">Crea tu primer slide para el carrusel publicitario.</p>
                            <a href="{{ route('carousel-slides.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primer Slide
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection