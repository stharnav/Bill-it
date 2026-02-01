<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Product;
use App\Models\SalesProduct;
use Illuminate\Support\Facades\DB;


class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::all();
        return view('sales.sale', compact('sales'), ['currentPage' => 'sales']);
    }

    public function search(Request $request)
    {
        return Product::where('name', 'LIKE', '%' . $request->search . '%')
            ->select('id', 'name', 'price')
            ->get();
    }

    public function getProduct($id)
    {
        return Product::findOrFail($id);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'mode_of_payment' => 'required',
            'products' => 'required|array',
            'payment_details' => 'nullable|string',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {

            /* ==========================
            CREATE SALE
            ========================== */
            $lastSale = Sales::latest()->first();
            $nextId = $lastSale ? $lastSale->id + 1 : 1;

            $invoiceNo = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            
            $sale = Sales::create([
                'invoice_no' => $invoiceNo, // auto invoice no
                'mode_of_payment' => $request->mode_of_payment,
                'payment_details' => $request->payment_details,
                'description' => $request->description,
                'discount' => $request->discount ?? 0,
            ]);

            /* ==========================
            INSERT SALE PRODUCTS
            ========================== */

            foreach ($request->products as $product) {
                SalesProduct::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product['id'],
                    'quantity'   => $product['qty'],
                    'price'      => Product::find($product['id'])->price,
                ]);
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Sale saved successfully');

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', 'Something went wrong');
        }
    }

}
