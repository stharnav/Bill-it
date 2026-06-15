<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Company;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
        public function index()
        {
            $categories =  Category::select('id', 'name')->get();
            $company = Company::first();
            return view('reports.sales-report', ['currentPage' => 'sales-report', 'categories' => $categories, 'company' => $company]);
        }

        public function search(Request $request)
        {
            $categories = Category::select('id', 'name')->get();

            $categoryId = $request->input('category_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $modeOfPayment = $request->input('mode_of_payment');

            $request->validate([
                'category_id' => 'nullable|exists:category,id',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'mode_of_payment' => 'nullable|integer|between:1,5',
            ]);

            $query = Sales::join('sales_product', 'sales.id', '=', 'sales_product.sale_id')
                ->select(
                    'sales.id',
                    'sales.bill_no',
                    'sales.created_at',
                    'sales.customer_name',
                    'sales.is_refund',
                    'sales.discount',
                    'sales.tax',
                    'sales.mode_of_payment',
                    DB::raw('SUM(sales_product.quantity * sales_product.price) as total_price')
                );

            // Mode of payment filter
            if ($modeOfPayment) {
                $query->where('sales.mode_of_payment', $modeOfPayment);
            }

            // Category filter — find sales that have products in the selected category
            if ($categoryId) {
                $query->whereIn('sales.id', function ($sub) use ($categoryId) {
                    $sub->select('sale_id')
                         ->from('sales_product')
                         ->join('product', 'sales_product.product_id', '=', 'product.id')
                         ->where('product.category_id', $categoryId);
                });
            }

            // Date filter
            if ($startDate && $endDate) {
                $query->whereBetween('sales.created_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay()
                ]);
            }

            $sales = $query
                ->groupBy(
                    'sales.id', 'sales.bill_no', 'sales.created_at',
                    'sales.customer_name', 'sales.is_refund', 'sales.discount',
                    'sales.tax', 'sales.mode_of_payment'
                )
                ->get();

            $company = Company::first();
            return view('reports.sales-report', ['currentPage' => 'sales-report', 'categories' => $categories, 'company' => $company], compact('sales'))
                ->with('success', 'Sales report generated successfully.');
        }
}
