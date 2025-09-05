@extends('layout')

@section('title', 'Detalles del Producto')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-eye"></i> Detalles del Producto</h2>
                <div>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Productos
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Información principal del producto -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ $product->name }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Imagen del producto -->
                                <div class="col-md-4">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                             class="img-fluid rounded shadow-sm" style="max-height: 300px; width: 100%; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height: 300px;">
                                            <div class="text-center">
                                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                                <p class="text-muted">Sin imagen</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Información del producto -->
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Descripción:</label>
                                        <p class="text-muted">{{ $product->description }}</p>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-sm-6">
                                            <label class="form-label fw-bold">Precio:</label>
                                            <p class="h4 text-success">${{ number_format($product->price, 2) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label fw-bold">Categoría:</label>
                                            <p>
                                                <span class="badge bg-secondary fs-6">{{ $product->category->name }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Estado:</label>
                                        <p>
                                            @if($product->available)
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-check-circle"></i> Disponible
                                                </span>
                                            @else
                                                <span class="badge bg-danger fs-6">
                                                    <i class="fas fa-times-circle"></i> No disponible
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary toggle-availability" 
                                                data-id="{{ $product->id }}" 
                                                data-available="{{ $product->available }}">
                                            @if($product->available)
                                                <i class="fas fa-eye-slash"></i> Marcar como No Disponible
                                            @else
                                                <i class="fas fa-eye"></i> Marcar como Disponible
                                            @endif
                                        </button>
                                        
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger delete-product" 
                                                data-id="{{ $product->id }}" 
                                                data-name="{{ $product->name }}">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Panel lateral con información adicional -->
                <div class="col-lg-4">
                    <!-- Información del sistema -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Información del Sistema</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">ID del Producto:</small>
                                <p class="mb-1">#{{ $product->id }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Fecha de Creación:</small>
                                <p class="mb-1">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Última Actualización:</small>
                                <p class="mb-1">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            <div class="mb-0">
                                <small class="text-muted">Tiempo desde creación:</small>
                                <p class="mb-0">{{ $product->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estadísticas del producto -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Estadísticas</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Total de Pedidos:</small>
                                <p class="mb-1 h5">{{ $product->orderItems()->count() }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">Cantidad Total Vendida:</small>
                                <p class="mb-1 h5">{{ $product->orderItems()->sum('quantity') }}</p>
                            </div>
                            
                            <div class="mb-0">
                                <small class="text-muted">Ingresos Generados:</small>
                                <p class="mb-0 h5 text-success">
                                    ${{ number_format($product->orderItems()->sum('subtotal'), 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el producto <strong id="productName"></strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer y eliminará todas las estadísticas asociadas.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Manejar cambio de disponibilidad
    $('.toggle-availability').click(function() {
        const productId = $(this).data('id');
        const button = $(this);
        
        $.post(`/products/${productId}/toggle-availability`, {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                // Actualizar el botón y el badge
                location.reload(); // Recargar para actualizar toda la información
            }
        })
        .fail(function() {
            showAlert('danger', 'Error al cambiar la disponibilidad del producto.');
        });
    });
    
    // Manejar eliminación de producto
    $('.delete-product').click(function() {
        const productId = $(this).data('id');
        const productName = $(this).data('name');
        
        $('#productName').text(productName);
        $('#deleteForm').attr('action', `/products/${productId}`);
        $('#deleteModal').modal('show');
    });
    
    // Función para mostrar alertas
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        $('.container-fluid').prepend(alertHtml);
        
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>
@endsection