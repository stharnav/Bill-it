<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
        public function index()
        {
            $categories =  Category::select('id', 'name')->get();
            return view('reports.sales-report', ['currentPage' => 'sales-report', 'categories' => $categories]);
        }

        public function search(Request $request)
        {   
            $categories =  Category::select('id', 'name')->get();

            $categoryId = $request->input('category_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $request->validate([
                'category_id' => 'nullable|exists:category,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            $query = DB::table('sales')
                ->join('sales_product', 'sales.id', '=', 'sales_product.sale_id')
                ->select(
                    'sales.id',
                    'sales.bill_no',
                    // 'sales.category_id',
                    'sales.created_at',
                    'sales.customer_name',
                    'sales.is_refund',
                    'sales.discount',
                    'sales.tax',
                    'sales.mode_of_payment',
                    DB::raw('SUM(sales_product.quantity * sales_product.price) as total_price')
                );

            // Category filter
            // if ($categoryId) {
            //     $query->where('sales.category_id', $categoryId);
            // }

            // Date filter
            if ($startDate && $endDate) {
                $query->whereBetween('sales.created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            $sales = $query
                // ->groupBy('sales.id', 'sales.bill_no', 'sales.category_id', 'sales.created_at')
                ->groupBy('sales.id', 'sales.bill_no', 'sales.created_at', 'sales.customer_name', 'sales.is_refund', 'sales.discount', 'sales.tax', 'sales.mode_of_payment')
                ->get();

            return view('reports.sales-report', ['currentPage' => 'sales-report', 'categories' => $categories], compact('sales'))
                ->with('success', 'Sales report generated successfully.');
        }
}
