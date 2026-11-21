<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReturnController extends Controller
{
    public function returnIndex(Request $request)
    {
        $user = auth()->user();
        $isSuperAdmin = $user->hasRole('Super-Admin');

        $query = Receipt::with([
            'originalReceipt',
            'party',
            'company',
            'branch',
            'creator',
            'items.product',
        ])
            ->whereIn('type', ['Sales-Return', 'Purchase-Return'])
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where('created_by', $user->id);
            });

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('receipt_no', 'like', "%{$search}%")
                    ->orWhere('so_no', 'like', "%{$search}%")
                    ->orWhere('po_no', 'like', "%{$search}%")
                    ->orWhere('inv_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")

                    ->orWhereHas('originalReceipt', function ($original) use ($search) {
                        $original->where('receipt_no', 'like', "%{$search}%")
                            ->orWhere('so_no', 'like', "%{$search}%")
                            ->orWhere('po_no', 'like', "%{$search}%")
                            ->orWhere('inv_no', 'like', "%{$search}%");
                    })

                    ->orWhereHas('party', function ($party) use ($search) {
                        $party->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })

                    ->orWhereHas('items.product', function ($product) use ($search) {
                        $product->where('name', 'like', "%{$search}%")
                            ->orWhere('product_code', 'like', "%{$search}%");
                    });
            });
        }

        // Return Type
        if (
            $request->filled('type') &&
            in_array($request->type, ['Sales-Return', 'Purchase-Return'])
        ) {

            $query->where('type', $request->type);
        }

        // Company Filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        // Branch Filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Status Filter
        if (
            $request->filled('status') &&
            in_array($request->status, ['Draft', 'Completed', 'Cancelled'])
        ) {

            $query->where('status', $request->status);
        }

        // Date Filter
        if ($request->filled('from_date')) {
            $query->whereDate('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('receipt_date', '<=', $request->to_date);
        }

        $summaryQuery = clone $query;

        $totalReturns = (clone $summaryQuery)->count();

        $totalReturnAmount = (clone $summaryQuery)->sum('total_amount');

        $salesReturnAmount = (clone $summaryQuery)
            ->where('type', 'Sales-Return')
            ->sum('total_amount');

        $purchaseReturnAmount = (clone $summaryQuery)
            ->where('type', 'Purchase-Return')
            ->sum('total_amount');

        $totalReturnQty = (clone $summaryQuery)->sum('total_qty');

        $perPage = (int) $request->per_page;

        if (!in_array($perPage, [15, 24, 50, 100])) {
            $perPage = 24;
        }

        $returns = $query->latest('id')
            ->paginate($perPage)
            ->withQueryString();

        $companies = Company::orderBy('name')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)
                        ->orWhere('created_by', $user->id);
                });
            })
            ->get();

        $branches = Branch::when(!$isSuperAdmin, function ($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
            ->orderBy('name')
            ->get();

        return view('BackEnd.Return.index', compact(
            'returns',
            'companies',
            'branches',
            'totalReturns',
            'totalReturnAmount',
            'salesReturnAmount',
            'purchaseReturnAmount',
            'totalReturnQty'
        ));
    }

    public function storeReturn(Request $request)
    {
        $request->validate([
            'original_receipt_id' => 'required|exists:receipts,id',
        ]);

        $user = auth()->user();

        try {

            $returnReceipt = DB::transaction(function () use ($request, $user) {

                $originalReceipt = Receipt::with('items')
                    ->where('id', $request->original_receipt_id)
                    ->when(!$user->hasRole('Super-Admin'), function ($query) use ($user) {
                        $query->where('created_by', $user->id);
                    })
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($originalReceipt->status !== 'Completed') {
                    throw ValidationException::withMessages([
                        'receipt' => 'Only completed invoices can be returned.',
                    ]);
                }

                if (!in_array($originalReceipt->type, ['Sales-Order', 'Purchase-Order'])) {
                    throw ValidationException::withMessages([
                        'receipt' => 'Invalid invoice type.',
                    ]);
                }

                $isSalesReturn = $originalReceipt->type === 'Sales-Order';

                $returnType = $isSalesReturn
                    ? 'Sales-Return'
                    : 'Purchase-Return';

                $returnItems = [];
                $totalQty = 0;
                $subTotal = 0;

                foreach ($originalReceipt->items->whereNotNull('product_id') as $item) {

                    $alreadyReturned = ReceiptItem::where('product_id', $item->product_id)
                        ->whereHas('receipt', function ($query) use ($originalReceipt, $returnType) {
                            $query->where('original_receipt_id', $originalReceipt->id)
                                ->where('type', $returnType)
                                ->where('status', 'Completed');
                        })
                        ->sum('qty');

                    $returnableQty = max(0, (float) $item->qty - (float) $alreadyReturned);

                    if ($returnableQty <= 0) {
                        continue;
                    }

                    $product = Product::where('id', $item->product_id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if (!$isSalesReturn) {

                        $availableStock = (float) StockTransaction::where('company_id', $originalReceipt->company_id)
                            ->where('branch_id', $originalReceipt->branch_id)
                            ->where('product_id', $item->product_id)
                            ->selectRaw('COALESCE(SUM(stock_in - stock_out), 0) as balance')
                            ->value('balance');

                        if ($returnableQty > $availableStock) {
                            throw ValidationException::withMessages([
                                'stock' => "Insufficient stock for {$product->name}. Available: {$availableStock}",
                            ]);
                        }
                    }

                    $rate = (float) $item->rate;
                    $amount = round($returnableQty * $rate, 2);

                    $returnItems[] = [
                        'original_item' => $item,
                        'product' => $product,
                        'qty' => $returnableQty,
                        'rate' => $rate,
                        'cost_price' => (float) $item->cost_price,
                        'amount' => $amount,
                    ];

                    $totalQty += $returnableQty;
                    $subTotal += $amount;
                }

                if (empty($returnItems)) {
                    throw ValidationException::withMessages([
                        'receipt' => 'All items have already been returned.',
                    ]);
                }

                $returnReceipt = Receipt::create([
                    'receipt_no' => 'RET-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),

                    'type' => $returnType,
                    'original_receipt_id' => $originalReceipt->id,

                    'company_id' => $originalReceipt->company_id,
                    'branch_id' => $originalReceipt->branch_id,
                    'party_id' => $originalReceipt->party_id,
                    'customer_company_id' => $originalReceipt->customer_company_id,

                    'receipt_date' => now()->toDateString(),

                    'customer_name' => $originalReceipt->customer_name,
                    'customer_phone' => $originalReceipt->customer_phone,

                    'remarks' => 'Return against ' . $originalReceipt->receipt_no,

                    'total_qty' => $totalQty,
                    'sub_total' => $subTotal,
                    'discount' => 0,
                    'vat' => 0,
                    'total_amount' => $subTotal,

                    'paid_amount' => 0,
                    'due_amount' => $subTotal,
                    'payment_status' => 'Pending',

                    'status' => 'Completed',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);

                foreach ($returnItems as $row) {

                    $item = $row['original_item'];
                    $product = $row['product'];
                    $qty = $row['qty'];

                    ReceiptItem::create([
                        'receipt_id' => $returnReceipt->id,
                        'category_id' => $item->category_id,
                        'account_head_id' => $item->account_head_id,
                        'product_id' => $item->product_id,
                        'qty' => $qty,
                        'rate' => $row['rate'],
                        'cost_price' => $row['cost_price'],
                        'amount' => $row['amount'],
                        'details' => 'Return against ' . $originalReceipt->receipt_no,
                    ]);

                    if ($isSalesReturn) {

                        $stockIn = $qty;
                        $stockOut = 0;

                        $product->current_stock = (float) $product->current_stock + $qty;
                    } else {

                        $stockIn = 0;
                        $stockOut = $qty;

                        $product->current_stock = (float) $product->current_stock - $qty;
                    }

                    $product->save();

                    $balance = (float) StockTransaction::where('company_id', $originalReceipt->company_id)
                        ->where('branch_id', $originalReceipt->branch_id)
                        ->where('product_id', $item->product_id)
                        ->selectRaw('COALESCE(SUM(stock_in - stock_out), 0) as balance')
                        ->value('balance');

                    $balance += $stockIn - $stockOut;

                    StockTransaction::create([
                        'company_id' => $originalReceipt->company_id,
                        'branch_id' => $originalReceipt->branch_id,
                        'product_id' => $item->product_id,
                        'receipt_id' => $returnReceipt->id,
                        'transaction_type' => $isSalesReturn
                            ? 'Sales Return'
                            : 'Purchase Return',
                        'stock_in' => $stockIn,
                        'stock_out' => $stockOut,
                        'balance' => $balance,
                        'transaction_date' => now()->toDateString(),
                        'remarks' => 'Return against ' . $originalReceipt->receipt_no,
                        'created_by' => $user->id,
                    ]);
                }

                return $returnReceipt;
            });

            return redirect()->back()->with(
                'success',
                'Return completed successfully. Return No: ' . $returnReceipt->receipt_no
            );
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {

            report($e);

            return redirect()->back()->with(
                'error',
                'Return failed. Please check the invoice and stock.'
            );
        }
    }
}
