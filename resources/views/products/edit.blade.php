@extends('layout')

@section('title', 'Editar Producto')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-edit"></i> Editar Producto: {{ $product->name }}</h2>
                <div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info me-2">
                        <i class="fas fa-eye"></i> Ver Producto
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Productos
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <!-- Nombre del producto -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $product->name) }}" 
                                               placeholder="Ej: Café Americano" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Precio -->
                                    <div class="col-md-6 mb-3">
                                        <label for="price" class="form-label">Precio <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', $product->price) }}" 
                                                   step="0.01" min="0" placeholder="0.00" required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Descripción <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Describe el producto..." required>{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Stock -->
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                               id="stock" name="stock" value="{{ old('stock', $product->stock) }}" 
                                               min="0" placeholder="0" required>
                                        <div class="form-text">Cantidad disponible en inventario</div>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Categoría -->
                                    <div class="col-md-6 mb-3">
                                        <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" name="category_id" required>
                                            <option value="">Seleccionar categoría...</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Disponibilidad -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Disponibilidad</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="available" name="available" value="1" 
                                                   {{ old('available', $product->available) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="available">
                                                Producto disponible para la venta
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imagen actual -->
                                @if($product->image)
                                    <div class="mb-3">
                                        <label class="form-label">Imagen Actual</label>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                                 class="img-thumbnail me-3" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                            <div>
                                                <p class="mb-1"><strong>{{ $product->name }}</strong></p>
                                                <small class="text-muted">Imagen actual del producto</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Nueva imagen -->
                                <div class="mb-4">
                                    <label for="image" class="form-label">
                                        {{ $product->image ? 'Cambiar Imagen' : 'Agregar Imagen' }}
                                    </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">
                                        Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB
                                        @if($product->image)
                                            <br><small class="text-warning">Si seleccionas una nueva imagen, reemplazará la actual.</small>
                                        @endif
                                    </div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Preview de nueva imagen -->
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>

                                <!-- Información adicional -->
                                <div class="alert alert-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small><strong>Creado:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <small><strong>Última actualización:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <div>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info me-2">
                                            <i class="fas fa-eye"></i> Ver Producto
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Actualizar Producto
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Preview de nueva imagen
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').attr('src', e.target.result);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });
    
    // Validación del formulario
    $('form').submit(function(e) {
        let isValid = true;
        
        // Validar campos requeridos
        $('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validar precio
        const price = parseFloat($('#price').val());
        if (isNaN(price) || price < 0) {
            isValid = false;
            $('#price').addClass('is-invalid');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, completa todos los campos requeridos correctamente.');
        }
    });
});
</script>
@endsection