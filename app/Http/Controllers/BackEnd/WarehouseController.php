<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\SerialNumber;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $purchases = Receipt::with('supplier')
            ->where('type', 'Purchase-Order')
            // ->where('status', 'Draft')
            // ->where('is_receive', false)
            ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                $query->where('created_by', $user->id);
            })
            ->latest()
            ->paginate(20);

        return view('BackEnd.Warehouse.index', compact('purchases'));
    }

    public function show(Receipt $receipt)
    {
        $receipt->load([
            'supplier',
            'items.product',
            'items.serialNumbers',
            'company',
            'branch',
        ]);

        return view('BackEnd.Warehouse.show', compact('receipt'));
    }

    public function updateSerial(Request $request, Receipt $receipt, ReceiptItem $receiptItem)
    {
        DB::beginTransaction();
        try {
            if ($receiptItem->receipt_id != $receipt->id) {
                throw new \Exception('Invalid purchase item.');
            }
            $product = Product::findOrFail($receiptItem->product_id);
            $serialJson = $request->input('serial_json', '[]');
            $serials = json_decode($serialJson, true);
            if (!is_array($serials)) {
                throw new \Exception('Invalid serial data.');
            }
            $serials = collect($serials)->map(function ($serial) {
                return strtoupper(trim((string) $serial));
            })->filter(function ($serial) {
                return $serial !== '';
            })->values()->toArray();
            if (count($serials) !== count(array_unique($serials))) {
                throw new \Exception('Duplicate serial number found.');
            }
            if (count($serials) > 0 && count($serials) != (int) $receiptItem->qty) {
                throw new \Exception(
                    $product->name . ' serial quantity does not match Qty. ' . 'Qty: ' . $receiptItem->qty . ', Serial: ' . count($serials)
                );
            }
            $companyId = $receipt->company_id ?? auth()->user()->company_id;
            foreach ($serials as $serial) {
                $exists = SerialNumber::where('company_id', $companyId)
                    ->where('serial_no', $serial)
                    ->where('receipt_id', '!=', $receipt->id)->exists();
                if ($exists) {
                    throw new \Exception('Duplicate Serial Number: ' . $serial);
                }
            }
            SerialNumber::where('receipt_item_id', $receiptItem->id)->delete();
            foreach ($serials as $serial) {
                SerialNumber::create([
                    'company_id' => $companyId,
                    'branch_id' => $receipt->branch_id ?? auth()->user()->branch_id,
                    'product_id' => $receiptItem->product_id,
                    'receipt_id' => $receipt->id,
                    'receipt_item_id' => $receiptItem->id,
                    'serial_no' => $serial,
                    'status' => $receipt->is_receive ? 'Available' : 'Pending',
                    'receive_date' => $receipt->is_receive ? today() : null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }
            DB::commit();
            return back()->with('success', 'Serial numbers updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
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
                $product->purchase_price = $item->rate;
                $product->save();
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
                    'created_by'       => auth()->id(),
                ]);
                SerialNumber::where('receipt_item_id', $item->id)
                    ->update([
                        'status'       => 'Available',
                        'receive_date' => today(),
                        'updated_by'   => auth()->id(),
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
