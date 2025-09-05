@extends('layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Principal')

@section('content')
<div class="row mb-4">
    <!-- Estadísticas principales -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Total Pedidos</h6>
                        <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Pedidos Pendientes</h6>
                        <h3 class="mb-0">{{ $stats['pending_orders'] }}</h3>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Ingresos Totales</h6>
                        <h3 class="mb-0">${{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-1">Pedidos Hoy</h6>
                        <h3 class="mb-0">{{ $stats['today_orders'] }}</h3>
                        <small class="opacity-75">${{ number_format($stats['today_revenue'], 2) }}</small>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Gráfico de ventas -->
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Ventas de los Últimos 7 Días
                </h5>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Productos Más Vendidos
                </h5>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    @foreach($topProducts as $index => $product)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                <span>{{ $product->name }}</span>
                            </div>
                            <span class="badge bg-success">{{ $product->total_sold }} vendidos</span>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">No hay datos disponibles</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Pedidos recientes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Pedidos Recientes
                </h5>
                <a href="{{ route('orders.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    Ver Todos
                </a>
            </div>
            <div class="card-body">
                @if($recentOrders->count() > 0)
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
                            <tbody id="recent-orders-table">
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>
                                            <div>
                                                <strong>{{ $order->customer_name }}</strong><br>
                                                <small class="text-muted">{{ $order->customer_email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <small>
                                                @foreach($order->orderItems->take(2) as $item)
                                                    {{ $item->quantity }}x {{ $item->product->name }}<br>
                                                @endforeach
                                                @if($order->orderItems->count() > 2)
                                                    <span class="text-muted">+{{ $order->orderItems->count() - 2 }} más...</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'preparing' => 'info',
                                                    'ready' => 'primary',
                                                    'completed' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentColors = [
                                                    'pending' => 'warning',
                                                    'paid' => 'success',
                                                    'failed' => 'danger',
                                                    'refunded' => 'info'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay pedidos recientes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Configurar gráfico de ventas
    const salesData = @json($salesChart);
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
            }),
            datasets: [{
                label: 'Ventas ($)',
                data: salesData.map(item => item.total),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Número de Pedidos',
                data: salesData.map(item => item.orders_count),
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                borderWidth: 3,
                fill: false,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Ventas ($)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Número de Pedidos'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Actualización en tiempo real de pedidos
    let lastCheck = new Date().toISOString();
    
    function checkNewOrders() {
        fetch(`{{ route('api.orders.realtime') }}?last_check=${lastCheck}`)
            .then(response => response.json())
            .then(data => {
                if (data.orders && data.orders.length > 0) {
                    // Mostrar notificación de nuevos pedidos
                    showNotification(`¡${data.orders.length} nuevo(s) pedido(s)!`, 'success');
                    
                    // Actualizar estadísticas (recargar página para simplicidad)
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
                lastCheck = data.last_check;
            })
            .catch(error => console.error('Error checking new orders:', error));
    }
    
    // Verificar nuevos pedidos cada 30 segundos
    setInterval(checkNewOrders, 30000);
    
    function showNotification(message, type = 'info') {
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <i class="fas fa-bell me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove después de 5 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
        
        // Notificación del navegador si está permitida
        if (Notification.permission === 'granted') {
            new Notification('Cafetería Admin', {
                body: message,
                icon: '/favicon.ico'
            });
        }
    }
    
    // Solicitar permiso para notificaciones
    if (Notification.permission === 'default') {
        Notification.requestPermission();
    }
</script>
@endsection