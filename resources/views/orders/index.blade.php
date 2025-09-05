@extends('layout')

@section('title', 'Gestión de Pedidos')
@section('page-title', 'Gestión de Pedidos')

@section('content')
<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Estado del Pedido</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparando</option>
                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Listo</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="payment_status" class="form-label">Estado del Pago</label>
                <select name="payment_status" id="payment_status" class="form-select">
                    <option value="">Todos los pagos</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Desde</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Hasta</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search me-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Lista de pedidos -->
<div class="card">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            Lista de Pedidos ({{ $orders->total() }} total)
        </h5>
        <button class="btn btn-outline-primary btn-sm" id="refreshOrders">
            <i class="fas fa-sync-alt me-1"></i>
            Actualizar
        </button>
    </div>
    <div class="card-body">
        @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Pago</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table">
                        @foreach($orders as $order)
                            <tr data-order-id="{{ $order->id }}">
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <div>
                                        <strong>{{ $order->customer_name }}</strong><br>
                                        <small class="text-muted">{{ $order->customer_email }}</small><br>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </div>
                                </td>
                                <td>
                                    <small>
                                        @foreach($order->orderItems->take(3) as $item)
                                            {{ $item->quantity }}x {{ $item->product->name }}<br>
                                        @endforeach
                                        @if($order->orderItems->count() > 3)
                                            <span class="text-muted">+{{ $order->orderItems->count() - 3 }} más...</span>
                                        @endif
                                    </small>
                                </td>
                                <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                <td>
                                    <select class="form-select form-select-sm status-select" data-order-id="{{ $order->id }}" data-type="status">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparando</option>
                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Listo</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completado</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm payment-select" data-order-id="{{ $order->id }}" data-type="payment_status">
                                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Pagado</option>
                                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Fallido</option>
                                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                                    </select>
                                </td>
                                <td>
                                    <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-success print-order" data-order-id="{{ $order->id }}" title="Imprimir">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $orders->appends(request()->query())->links('custom.pagination') }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No se encontraron pedidos</h5>
                <p class="text-muted">No hay pedidos que coincidan con los filtros seleccionados.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cambio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirmar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let pendingUpdate = null;
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    // Manejar cambios de estado
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('status-select') || e.target.classList.contains('payment-select')) {
            const orderId = e.target.dataset.orderId;
            const type = e.target.dataset.type;
            const newValue = e.target.value;
            const oldValue = e.target.dataset.oldValue || e.target.querySelector('option[selected]')?.value;
            
            // Guardar el valor anterior
            e.target.dataset.oldValue = oldValue;
            
            // Configurar la actualización pendiente
            pendingUpdate = {
                orderId: orderId,
                type: type,
                newValue: newValue,
                oldValue: oldValue,
                element: e.target
            };
            
            // Mostrar modal de confirmación
            const typeText = type === 'status' ? 'estado del pedido' : 'estado del pago';
            document.getElementById('confirmMessage').textContent = 
                `¿Está seguro de cambiar el ${typeText} del pedido #${orderId} a "${newValue}"?`;
            
            confirmModal.show();
        }
    });

    // Confirmar actualización
    document.getElementById('confirmAction').addEventListener('click', function() {
        if (pendingUpdate) {
            updateOrderStatus(pendingUpdate);
            confirmModal.hide();
        }
    });

    // Cancelar actualización
    document.getElementById('confirmModal').addEventListener('hidden.bs.modal', function() {
        if (pendingUpdate && pendingUpdate.element) {
            // Revertir al valor anterior si se cancela
            pendingUpdate.element.value = pendingUpdate.oldValue;
        }
        pendingUpdate = null;
    });

    function updateOrderStatus(update) {
        const { orderId, type, newValue, element } = update;
        
        // Deshabilitar el select mientras se actualiza
        element.disabled = true;
        
        // Preparar datos
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        
        if (type === 'status') {
            formData.append('status', newValue);
            // Mantener el estado de pago actual
            const paymentSelect = document.querySelector(`select[data-order-id="${orderId}"][data-type="payment_status"]`);
            formData.append('payment_status', paymentSelect.value);
        } else {
            formData.append('payment_status', newValue);
            // Mantener el estado del pedido actual
            const statusSelect = document.querySelector(`select[data-order-id="${orderId}"][data-type="status"]`);
            formData.append('status', statusSelect.value);
        }
        
        fetch(`/orders/${orderId}/update-status`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Estado actualizado correctamente', 'success');
                // Actualizar el valor anterior
                element.dataset.oldValue = newValue;
            } else {
                showAlert('Error al actualizar el estado', 'danger');
                // Revertir al valor anterior
                element.value = update.oldValue;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error de conexión', 'danger');
            // Revertir al valor anterior
            element.value = update.oldValue;
        })
        .finally(() => {
            element.disabled = false;
        });
    }

    // Actualizar pedidos
    document.getElementById('refreshOrders').addEventListener('click', function() {
        window.location.reload();
    });

    // Imprimir pedido
    document.addEventListener('click', function(e) {
        if (e.target.closest('.print-order')) {
            const orderId = e.target.closest('.print-order').dataset.orderId;
            window.open(`/orders/${orderId}?print=1`, '_blank');
        }
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

    // Actualización automática cada 60 segundos
    setInterval(function() {
        // Solo actualizar si no hay modales abiertos
        if (!document.querySelector('.modal.show')) {
            const currentUrl = new URL(window.location);
            fetch(currentUrl.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Actualizar solo la tabla de pedidos
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector('#orders-table');
                if (newTable) {
                    document.querySelector('#orders-table').innerHTML = newTable.innerHTML;
                }
            })
            .catch(error => console.error('Error updating orders:', error));
        }
    }, 60000);
</script>
@endsection