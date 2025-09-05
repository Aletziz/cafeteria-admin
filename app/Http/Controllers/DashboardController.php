<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', Carbon::today())->count(),
            'today_revenue' => Order::whereDate('created_at', Carbon::today())
                                   ->where('payment_status', 'paid')
                                   ->sum('total_amount'),
        ];

        // Pedidos recientes
        $recentOrders = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Productos más vendidos
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Ventas por día (últimos 7 días)
        $salesChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('dashboard', compact('stats', 'recentOrders', 'topProducts', 'salesChart'));
    }

    /**
     * Gestión de pedidos
     */
    public function orders(Request $request)
    {
        $query = Order::with('orderItems.product');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('orders.index', compact('orders'));
    }

    /**
     * Ver detalles de un pedido
     */
    public function showOrder($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Actualizar estado del pedido
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:pending,preparing,ready,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $order = Order::findOrFail($id);
        
        $updateData = [];
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }
        if ($request->has('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }
        
        $order->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado correctamente'
            ]);
        }

        return back()->with('success', 'Estado del pedido actualizado correctamente');
    }

    /**
     * Obtener pedidos en tiempo real (AJAX)
     */
    public function getRealtimeOrders(Request $request)
    {
        $lastCheck = $request->get('last_check');
        
        $query = Order::with('orderItems.product')
            ->orderBy('created_at', 'desc');
            
        if ($lastCheck) {
            $query->where('created_at', '>', $lastCheck);
        } else {
            $query->take(20);
        }
        
        $orders = $query->get();
        
        return response()->json([
            'orders' => $orders,
            'last_check' => now()->toISOString()
        ]);
    }
}
