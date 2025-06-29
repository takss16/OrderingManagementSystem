<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all accepted orders.
     *
     * @return \Illuminate\View\View
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function destroy($id)
{
    $item = OrderItem::findOrFail($id);
    $item->delete();

    return back()->with('success', 'Item canceled successfully.');
}
public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:100',
        'cart_items'    => 'required|json',
        'cart_total'    => 'required|numeric|min:0',
        'table_id'      => 'required|exists:resto_tables,id',
    ]);

    $order = Order::create([
        'customer_name' => $request->customer_name,
        'table_id'      => $request->table_id,
        'total_amount'  => $request->cart_total,
        'status'        => 'pending',
    ]);

    foreach (json_decode($request->cart_items, true) as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'name'     => $item['name'],
            'quantity' => $item['qty'],
            'price'    => $item['price'],
        ]);
    }

    // 🔁 Redirect to the order receipt page (with table_id)
    return redirect()->route('order.index', ['table_id' => $request->table_id])
                     ->with('success', 'Order placed successfully!');
}

public function index(Request $request)
{
    $tableId = $request->input('table_id');

    $order = Order::with(['table', 'orderItems'])
                  ->where('table_id', $tableId)
                  ->where('status', 'pending')
                  ->latest()
                  ->first();

    if (!$order) {
        return redirect()->route('table.view', ['id' => $tableId])
                         ->with('error', 'No recent order found.');
    }

    return view('order.success', compact('order', 'tableId'));
}


        public function accepted()
    {
        $orders = Order::with(['table', 'orderItems'])
                       ->where('status', 'accept')
                       ->latest()
                       ->get();

        return view('order.accepted', compact('orders'));
    }



public function showSales()
{
    $order = Order::with(['table', 'orderItems'])->latest()->first();

    return view('order.success', compact('order'));
}

public function salesReport(Request $request)
{
    $start = $request->start_date ?? now()->toDateString();
    $end = $request->end_date ?? now()->toDateString();

    $orders = Order::whereDate('created_at', '>=', $start)
                   ->whereDate('created_at', '<=', $end)
                   ->where('status', 'served')
                   ->with('orderItems', 'table')
                   ->latest()
                   ->get();

    // Fetch top-selling items (aggregated)
    $topItems = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereDate('orders.created_at', '>=', $start)
        ->whereDate('orders.created_at', '<=', $end)
        ->where('orders.status', 'served')
        ->select('order_items.name', 
                 DB::raw('SUM(order_items.quantity) as total_qty'),
                 DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
        ->groupBy('order_items.name')
        ->orderByDesc('total_sales')
        ->get();

        $totalSales = $orders->sum('total_amount');

    return view('order.sales', compact('orders', 'start', 'end', 'topItems', 'totalSales'));
} 
public function exportPdf(Request $request)
{
    $start = $request->start_date ?? now()->toDateString();
    $end = $request->end_date ?? now()->toDateString();

    $orders = Order::whereDate('created_at', '>=', $start)
                   ->whereDate('created_at', '<=', $end)
                   ->where('status', 'served')
                   ->with('orderItems', 'table')
                   ->latest()
                   ->get();

    $topItems = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->whereDate('orders.created_at', '>=', $start)
        ->whereDate('orders.created_at', '<=', $end)
        ->where('orders.status', 'served')
        ->select('order_items.name', 
                 DB::raw('SUM(order_items.quantity) as total_qty'),
                 DB::raw('SUM(order_items.quantity * order_items.price) as total_sales'))
        ->groupBy('order_items.name')
        ->orderByDesc('total_sales')
        ->get();

    $totalSales = $orders->sum('total_amount');

    // Export PDF
  $pdf = Pdf::loadView('order.pdf', compact('orders', 'start', 'end', 'topItems', 'totalSales'));
return $pdf->download("served_sales_report_{$start}_to_{$end}.pdf");    
}

public function manage(Request $request)
{
    $status = $request->status ?? null;
    $query = Order::with(['orderItems', 'table'])->latest('created_at');

    if ($status) {
        $query->where('status', $status);
    }

    $orders = $query->get();

    return view('order.manage', compact('orders'));
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,accept,served',
    ]);

    $order->update(['status' => $request->status]);

    return back()->with('success', 'Order status updated!');
}


}
