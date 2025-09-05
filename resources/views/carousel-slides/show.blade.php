@extends('layout')

@section('page-title', 'Detalles del Slide')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detalles del Slide</h3>
                    <div class="card-tools">
                        <a href="{{ route('carousel-slides.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('carousel-slides.edit', $carouselSlide) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5>Vista Previa del Slide</h5>
                                <div class="carousel-slide-preview" 
                                     style="background: {{ $carouselSlide->background_gradient }}; 
                                            min-height: 300px; 
                                            border-radius: 15px; 
                                            position: relative; 
                                            overflow: hidden; 
                                            display: flex; 
                                            align-items: center; 
                                            padding: 2rem;">
                                    
                                    <!-- Imagen de fondo -->
                                    <div style="position: absolute; 
                                                top: 0; 
                                                right: 0; 
                                                width: 50%; 
                                                height: 100%; 
                                                background-image: url('{{ $carouselSlide->image_url }}'); 
                                                background-size: cover; 
                                                background-position: center; 
                                                opacity: 0.8;"></div>
                                    
                                    <!-- Contenido -->
                                    <div style="position: relative; z-index: 2; color: white; max-width: 50%;">
                                        @if($carouselSlide->badge_text)
                                            <span class="badge {{ $carouselSlide->badge_color }} mb-2">{{ $carouselSlide->badge_text }}</span>
                                        @endif
                                        
                                        <h3 style="color: white; font-weight: bold; margin-bottom: 1rem;">{{ $carouselSlide->title }}</h3>
                                        
                                        <p style="color: rgba(255,255,255,0.9); margin-bottom: 1rem;">{{ $carouselSlide->description }}</p>
                                        
                                        @if($carouselSlide->price)
                                            <div style="font-size: 1.5rem; font-weight: bold; color: #ffd700; margin-bottom: 1rem;">{{ $carouselSlide->price }}</div>
                                        @endif
                                        
                                        @if($carouselSlide->button_url)
                                            <a href="{{ $carouselSlide->button_url }}" class="btn btn-light" style="border-radius: 25px; padding: 0.5rem 1.5rem;">
                                                {{ $carouselSlide->button_text }}
                                            </a>
                                        @else
                                            <button class="btn btn-light" style="border-radius: 25px; padding: 0.5rem 1.5rem;">
                                                {{ $carouselSlide->button_text }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5>Información del Slide</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Título:</strong></td>
                                        <td>{{ $carouselSlide->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descripción:</strong></td>
                                        <td>{{ $carouselSlide->description }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Orden:</strong></td>
                                        <td>{{ $carouselSlide->order }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Estado:</strong></td>
                                        <td>
                                            @if($carouselSlide->is_active)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-secondary">Inactivo</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>URL de Imagen:</strong></td>
                                        <td>
                                            <a href="{{ $carouselSlide->image_url }}" target="_blank" class="text-break">
                                                {{ Str::limit($carouselSlide->image_url, 50) }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Texto del Botón:</strong></td>
                                        <td>{{ $carouselSlide->button_text }}</td>
                                    </tr>
                                    @if($carouselSlide->button_url)
                                        <tr>
                                            <td><strong>URL del Botón:</strong></td>
                                            <td>
                                                <a href="{{ $carouselSlide->button_url }}" target="_blank" class="text-break">
                                                    {{ Str::limit($carouselSlide->button_url, 50) }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($carouselSlide->badge_text)
                                        <tr>
                                            <td><strong>Badge:</strong></td>
                                            <td>
                                                <span class="badge {{ $carouselSlide->badge_color }}">{{ $carouselSlide->badge_text }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    @if($carouselSlide->price)
                                        <tr>
                                            <td><strong>Precio:</strong></td>
                                            <td>{{ $carouselSlide->price }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Creado:</strong></td>
                                        <td>{{ $carouselSlide->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Actualizado:</strong></td>
                                        <td>{{ $carouselSlide->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('carousel-slides.edit', $carouselSlide) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar Slide
                                    </a>
                                </div>
                                <div>
                                    <form action="{{ route('carousel-slides.destroy', $carouselSlide) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este slide?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Eliminar Slide
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection