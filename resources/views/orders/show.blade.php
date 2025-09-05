@extends('layout')

@section('title', 'Pedido #' . $order->id)
@section('page-title', 'Detalles del Pedido #' . $order->id)

@section('content')
<div class="row">
    <!-- Información del pedido -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Pedido
                </h5>
                <div>
                    <button class="btn btn-outline-primary btn-sm me-2" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>
                        Imprimir
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Datos del Cliente</h6>
                        <p><strong>Nombre:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p><strong>Teléfono:</strong> {{ $order->customer_phone }}</p>
                        @if($order->customer_address)
                            <p><strong>Dirección:</strong> {{ $order->customer_address }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Detalles del Pedido</h6>
                        <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        <p><strong>Total:</strong> <span class="h5 text-success">${{ number_format($order->total_amount, 2) }}</span></p>
                        <p>
                            <strong>Estado:</strong>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'preparing' => 'info',
                                    'ready' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} ms-2">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p>
                            <strong>Pago:</strong>
                            @php
                                $paymentColors = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'danger',
                                    'refunded' => 'info'
                                ];
                            @endphp
                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }} ms-2">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos del pedido -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Productos Pedidos
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->image)
                                                <img src="{{ asset($item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded me-3" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $item->product->name }}</strong>
                                                @if($item->product->description)
                                                    <br><small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->quantity }}</span>
                                    </td>
                                    <td><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="h5 text-success">${{ number_format($order->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de acciones -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Acciones
                </h5>
            </div>
            <div class="card-body">
                <form id="updateStatusForm">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">Estado del Pedido</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparando</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Listo</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Estado del Pago</label>
                        <select name="payment_status" id="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Pagado</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Fallido</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-1"></i>
                        Actualizar Estado
                    </button>
                </form>
            </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Estadísticas Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $order->orderItems->sum('quantity') }}</h4>
                            <small class="text-muted">Productos</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-1">${{ number_format($order->total_amount, 2) }}</h4>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-12">
                        <small class="text-muted">Tiempo transcurrido:</small><br>
                        <strong>{{ $order->created_at->diffForHumans() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial del pedido -->
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    Historial
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pedido Creado</h6>
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                    </div>
                    
                    @if($order->status != 'pending')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Estado: {{ ucfirst($order->status) }}</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->payment_status == 'paid')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pago Confirmado</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -23px;
        top: 5px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        border-left: 3px solid #007bff;
    }
    
    @media print {
        .card-header .btn,
        .timeline,
        .col-lg-4 {
            display: none !important;
        }
        
        .col-lg-8 {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Actualizar estado del pedido
    document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Deshabilitar botón
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Actualizando...';
        
        fetch(`{{ route('orders.update-status', $order->id) }}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Recargar la página después de 1 segundo
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert('Error al actualizar el estado', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error de conexión', 'danger');
        })
        .finally(() => {
            // Rehabilitar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
</script>
@endsection