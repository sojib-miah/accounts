<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $purchases = Receipt::with('supplier')->where('type', 'Purchase-Order')->where('is_receive', false)->latest()->paginate(20);

        return view('BackEnd.Warehouse.index', compact('purchases'));
    }

    public function show(Receipt $receipt)
    {
        $receipt->load(
            'supplier',
            'items.product'
        );

        return view('BackEnd.Warehouse.show', compact('receipt'));
    }

    public function receive(Request $request, Receipt $receipt)
    {
        if ($receipt->is_receive) {
            return back()->with('error', 'This purchase has already been received.');
        }
        DB::beginTransaction();
        try {
            $receipt->load('items.product');
            foreach ($receipt->items as $item) {
                $product = $item->product;
                $product->increment('current_stock', $item->qty);
                $product->update([
                    'purchase_price' => $item->rate
                ]);
                $product->refresh();
                StockTransaction::create([
                    'company_id'       => $receipt->company_id,
                    'branch_id'        => $receipt->branch_id,
                    'product_id'       => $product->id,
                    'receipt_id'       => $receipt->id,
                    'transaction_type' => 'Purchase',
                    'stock_in'         => $item->qty,
                    'stock_out'        => 0,
                    'balance'          => $product->current_stock,
                    'transaction_date' => $receipt->receipt_date,
                    'remarks'          => 'Purchase Receive',
                    'created_by'       => auth()->id()
                ]);
            }
            $receipt->update([
                'is_receive'   => true,
                'received_date' => now()->toDateString(),
                'received_by'  => auth()->id(),
                'status'       => 'Completed'
            ]);
            DB::commit();
            return redirect()->route('warehouse.index')->with('success', 'Goods received successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
