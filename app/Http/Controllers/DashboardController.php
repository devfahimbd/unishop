<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Today's Sales
        $todaySales = Sale::where('user_id', $userId)->whereDate('sale_date', today())->sum('total_amount');
        $todaySalesCount = Sale::where('user_id', $userId)->whereDate('sale_date', today())->count();

        // Total Products
        $totalProducts = Product::where('user_id', $userId)->count();

        // Total Customers
        $totalCustomers = Customer::where('user_id', $userId)->count();

        // Total Stock Value
        $totalStockValue = Product::where('user_id', $userId)
            ->selectRaw('SUM(stock_quantity * purchase_price) as total_value')
            ->value('total_value') ?? 0;

        // Low Stock Products
        $lowStockProducts = Product::where('user_id', $userId)
            ->whereColumn('stock_quantity', '<=', 'alert_quantity')
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        // Recent Sales (Last 5)
        $recentSales = Sale::where('user_id', $userId)
            ->with(['customer', 'saleItems'])
            ->latest()
            ->take(5)
            ->get();

        // Top Selling Products (Last 30 days)
        $topProducts = SaleItem::where('user_id', $userId)
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereHas('sale', function ($q) use ($userId) {
                $q->where('user_id', $userId)->whereDate('sale_date', '>=', now()->subDays(30));
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'todaySales',
            'todaySalesCount',
            'totalProducts',
            'totalCustomers',
            'totalStockValue',
            'lowStockProducts',
            'recentSales',
            'topProducts'
        ));
    }

    public function salesChartData()
    {
        $userId = auth()->id();
        $days = 7;

        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $sales = Sale::where('user_id', $userId)
                ->whereDate('sale_date', $date->format('Y-m-d'))
                ->sum('total_amount');
            $data[] = [
                'date' => $date->format('M d'),
                'sales' => (float) $sales,
            ];
        }

        return response()->json($data);
    }
}
