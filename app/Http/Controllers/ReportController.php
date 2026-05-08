<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailySales(Request $request)
    {
        $userId = auth()->id();
        $date = $request->date ?? today()->format('Y-m-d');

        $sales = Sale::where('user_id', $userId)
            ->whereDate('sale_date', $date)
            ->with(['customer', 'saleItems'])
            ->latest()
            ->paginate(15);

        $totalSales = $sales->sum('total_amount');
        $totalItems = SaleItem::where('user_id', $userId)
            ->whereHas('sale', function ($q) use ($userId, $date) {
                $q->where('user_id', $userId)->whereDate('sale_date', $date);
            })
            ->sum('quantity');

        return view('reports.daily-sales', compact('sales', 'date', 'totalSales', 'totalItems'));
    }

    public function monthlySales(Request $request)
    {
        $userId = auth()->id();
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $sales = Sale::where('user_id', $userId)
            ->whereMonth('sale_date', $month)
            ->whereYear('sale_date', $year)
            ->with(['customer'])
            ->latest()
            ->paginate(15);

        $totalSales = $sales->sum('total_amount');
        $totalDiscount = $sales->sum('discount_amount');
        $totalVat = $sales->sum('vat_amount');
        $totalDue = $sales->sum('due_amount');

        // Daily breakdown
        $dailyBreakdown = Sale::where('user_id', $userId)
            ->whereMonth('sale_date', $month)
            ->whereYear('sale_date', $year)
            ->selectRaw('DATE(sale_date) as date, SUM(total_amount) as total, COUNT(*) as order_count')
            ->groupByRaw('DATE(sale_date)')
            ->orderBy('date')
            ->get();

        return view('reports.monthly-sales', compact(
            'sales', 'month', 'year', 'totalSales', 'totalDiscount', 'totalVat', 'totalDue', 'dailyBreakdown'
        ));
    }

    public function profit(Request $request)
    {
        $userId = auth()->id();
        $dateFrom = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? now()->format('Y-m-d');

        // Total Sales Revenue
        $salesRevenue = Sale::where('user_id', $userId)
            ->whereBetween('sale_date', [$dateFrom, $dateTo])
            ->sum('total_amount');

        // Total Cost of Goods Sold (from sale_items using purchase_price)
        $costOfGoods = SaleItem::where('user_id', $userId)
            ->whereHas('sale', function ($q) use ($userId, $dateFrom, $dateTo) {
                $q->where('user_id', $userId)->whereBetween('sale_date', [$dateFrom, $dateTo]);
            })
            ->get()
            ->sum(function ($item) {
                return $item->quantity * ($item->product->purchase_price ?? 0);
            });

        // Total Expenses
        $totalExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->sum('amount');

        $grossProfit = $salesRevenue - $costOfGoods;
        $netProfit = $grossProfit - $totalExpenses;
        $profitMargin = $salesRevenue > 0 ? (($netProfit / $salesRevenue) * 100) : 0;

        return view('reports.profit', compact(
            'salesRevenue', 'costOfGoods', 'totalExpenses', 'grossProfit', 'netProfit', 'profitMargin', 'dateFrom', 'dateTo'
        ));
    }

    public function stock(Request $request)
    {
        $userId = auth()->id();

        $products = Product::where('user_id', auth()->id())
            ->with('category')
            ->when($request->search, function ($q) use ($request) {
                $q->search($request->search);
            })
            ->when($request->category_id, function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->latest()
            ->paginate(15);

        $totalStockValue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->purchase_price;
        });

        $totalSellingValue = $products->sum(function ($product) {
            return $product->stock_quantity * $product->selling_price;
        });

        $totalProducts = Product::where('user_id', $userId)->count();
        $outOfStock = Product::where('user_id', $userId)->where('stock_quantity', 0)->count();

        return view('reports.stock', compact(
            'products', 'totalStockValue', 'totalSellingValue', 'totalProducts', 'outOfStock'
        ));
    }

    public function lowStock()
    {
        $products = Product::where('user_id', auth()->id())
            ->with('category')
            ->whereColumn('stock_quantity', '<=', 'alert_quantity')
            ->orderBy('stock_quantity', 'asc')
            ->paginate(15);

        return view('reports.low-stock', compact('products'));
    }
}
