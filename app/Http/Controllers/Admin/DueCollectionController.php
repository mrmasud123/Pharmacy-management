<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Transaction;
use App\Models\User;
use App\Services\DueCollectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DueCollectionController extends Controller
{
    public function __construct(protected DueCollectionService $dueCollectionService){}
    public function index(){

        return view('admin.collection.index',['title' => "Collections"]);
    }

    public function customerInvoices(Customer $customer){
        $customer = Customer::with('sales.items.product', 'sales.items.productBatch')->findOrFail($customer->id);
        $title="All Invoices";
        return view('admin.collection.invoices', compact('customer','title'));
    }

    public function payDue(Request $request, Sale $sale)
    {
        $dueCollection=$this->dueCollectionService->dueCollection($request,$sale);

        if($dueCollection){
            return ApiResponseHelper::success('Payment of ' . number_format($request->amount, 2) . ' recorded for ' . $sale->invoice_no,[],200);

        }else{
            return ApiResponseHelper::error('Something went wrong.');
        }
    }

    public function edit(Sale $sale){
        $sale->load(['items.product', 'items.productBatch', 'customer']);
        $products = Product::with('batches')->orderBy('name')->get();
        $title= "Edit Invoice";
        return view('admin.collection.edit-invoice', compact('sale', 'products','title'));
    }

    public function update(Request $request, Sale $sale)
    {
        $this->dueCollectionService->updateDueCollection($request,$sale);

        return ApiResponseHelper::success('Invoice ' . $sale->invoice_no . ' updated successfully.');

    }

    public function paymentHistory(Customer $customer)
    {

        $transactions = Transaction::where('customer_id', $customer->id)
            ->with('transactionable', 'creator')
            ->latest()
            ->get();

        $totalCredit = Transaction::where('customer_id', $customer->id)
            ->where('direction', 'credit')
            ->sum('amount');

        $totalDebit = Transaction::where('customer_id', $customer->id)
            ->where('direction', 'debit')
            ->sum('amount');
        $title= "Payment History";
        return view('admin.collection.payment-history', compact(
            'title','customer', 'transactions', 'totalCredit', 'totalDebit'
        ));
    }
    public function data(Request $request)
    {
        return DataTables::of(
            Sale::query()
                ->selectRaw('
                    customers.id,
                    customers.name,
                    customers.phone,
                    COUNT(DISTINCT sales.id) as items_count,
                    SUM(sales.total) as total,
                    SUM(sales.grand_total) as grand_total,
                    SUM(sales.tax) as tax,
                    SUM(sales.discount) as discount,
                    SUM(sales.paid) as paid,
                    SUM(sales.total + sales.tax - sales.discount - sales.paid) as due
                ')
                ->join('customers', 'customers.id', '=', 'sales.customer_id')
                ->whereNotNull('sales.customer_id')
                ->groupBy('customers.id', 'customers.name', 'customers.phone')
                ->orderByRaw('SUM(sales.grand_total - sales.paid) DESC')
                ->when($request->filled('customer_id'), function ($q) use ($request){
                    $q->where('customers.id', $request->customer_id);
                })
                ->when($request->filled('collection_date'), function($q) use ($request){
                    $q->whereDate('sales.sale_date', $request->collection_date);
                })
        )

            ->addColumn('customer_name', function ($sale) {
                return '
                    <div class="p-2">
                        <div class="font-medium">'.e($sale->name).'</div>
                        <div class="text-xs text-gray-500">'.e($sale->phone).'</div>
                    </div>
                ';
            })
        ->addColumn('items_count', function ($sale) {
            return $sale->items_count;
        })
            ->addColumn('date', function ($sale) {
                return Carbon::parse($sale->sale_date)->format('d M Y H:i');
            })
        ->addColumn('status_badge', function ($sale) {
            if ($sale->due > 0) {
                return '<span class="inline-flex items-center px-2 py-1 text-xs font-medium                 rounded-full bg-red-100 text-red-700 dark:bg-red-900/40                     dark:text-red-300">
                               Pending
                    </span>';
            }
                return '<span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                    Paid
                </span>';
            })

            ->addColumn('action', function ($sale) {

                return '
                    <div class="flex  gap-2">
                        <a href="'.route('admin.collections.customers.invoices', $sale->id).'" class="px-3 py-1 text-xs bg-blue-500 hover:bg-blue-600 text-white rounded">
                            View
                        </a>

                        <a href="'.route('admin.collections.payment.history', $sale->id).'" class="px-3 py-1 text-xs bg-green-500 hover:bg-green-600 text-white rounded">
                            Payment history
                        </a>
                    </div>';
            })
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->where('customers.name', 'LIKE', "%{$keyword}%");
            })
            ->rawColumns(['customer_name', 'status_badge', 'action'])

            ->make(true);

    }
}
