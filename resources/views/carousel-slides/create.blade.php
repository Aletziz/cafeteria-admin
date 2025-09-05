@extends('layout')

@section('page-title', 'Crear Nuevo Slide')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Crear Nuevo Slide del Carrusel</h3>
                    <div class="card-tools">
                        <a href="{{ route('carousel-slides.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('carousel-slides.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Título *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order" class="form-label">Orden *</label>
                                    <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                           id="order" name="order" value="{{ old('order', 0) }}" min="0" required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image_url" class="form-label">URL de la Imagen *</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url') }}" required>
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Ingresa la URL completa de la imagen (ej: https://ejemplo.com/imagen.jpg)</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_text" class="form-label">Texto del Botón *</label>
                                    <input type="text" class="form-control @error('button_text') is-invalid @enderror" 
                                           id="button_text" name="button_text" value="{{ old('button_text', 'Ver Más') }}" required>
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button_url" class="form-label">URL del Botón</label>
                                    <input type="url" class="form-control @error('button_url') is-invalid @enderror" 
                                           id="button_url" name="button_url" value="{{ old('button_url') }}">
                                    @error('button_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="background_gradient" class="form-label">Gradiente de Fondo *</label>
                            <select class="form-control @error('background_gradient') is-invalid @enderror" 
                                    id="background_gradient" name="background_gradient" required>
                                <option value="linear-gradient(135deg, #667eea 0%, #764ba2 100%)" 
                                        {{ old('background_gradient') == 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' ? 'selected' : '' }}>
                                    Azul a Púrpura
                                </option>
                                <option value="linear-gradient(135deg, #f093fb 0%, #f5576c 100%)" 
                                        {{ old('background_gradient') == 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' ? 'selected' : '' }}>
                                    Rosa a Rojo
                                </option>
                                <option value="linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)" 
                                        {{ old('background_gradient') == 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)' ? 'selected' : '' }}>
                                    Azul Claro
                                </option>
                                <option value="linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)" 
                                        {{ old('background_gradient') == 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)' ? 'selected' : '' }}>
                                    Verde a Turquesa
                                </option>
                                <option value="linear-gradient(135deg, #fa709a 0%, #fee140 100%)" 
                                        {{ old('background_gradient') == 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)' ? 'selected' : '' }}>
                                    Rosa a Amarillo
                                </option>
                            </select>
                            @error('background_gradient')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="badge_text" class="form-label">Texto del Badge</label>
                                    <input type="text" class="form-control @error('badge_text') is-invalid @enderror" 
                                           id="badge_text" name="badge_text" value="{{ old('badge_text') }}">
                                    @error('badge_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="badge_color" class="form-label">Color del Badge *</label>
                                    <select class="form-control @error('badge_color') is-invalid @enderror" 
                                            id="badge_color" name="badge_color" required>
                                        <option value="bg-primary" {{ old('badge_color', 'bg-primary') == 'bg-primary' ? 'selected' : '' }}>Azul</option>
                                        <option value="bg-success" {{ old('badge_color') == 'bg-success' ? 'selected' : '' }}>Verde</option>
                                        <option value="bg-warning" {{ old('badge_color') == 'bg-warning' ? 'selected' : '' }}>Amarillo</option>
                                        <option value="bg-danger" {{ old('badge_color') == 'bg-danger' ? 'selected' : '' }}>Rojo</option>
                                        <option value="bg-info" {{ old('badge_color') == 'bg-info' ? 'selected' : '' }}>Cian</option>
                                        <option value="bg-dark" {{ old('badge_color') == 'bg-dark' ? 'selected' : '' }}>Negro</option>
                                    </select>
                                    @error('badge_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Precio</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" placeholder="ej: $15.99">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" 
                                               name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Slide Activo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('carousel-slides.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear Slide</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection