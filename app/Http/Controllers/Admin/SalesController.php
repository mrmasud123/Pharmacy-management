<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use Illuminate\Http\Request;
use App\Services\SaleService;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product;

use App\Models\ProductBatch;

use App\Models\Sale;

class SalesController extends Controller
{
    public function index(){
        $sales = collect([
            (object)[
                'id' => 1,
                'invoice_id' => 'INV-1001',
                'sr_invoice' => 'SR-5001',
                'date' => now()->subDays(1),
                'customer' => (object)['name' => 'John Doe'],
                'payable_amount' => 1500,
                'paid_amount' => 1200,
                'due_amount' => 300,
            ],
            (object)[
                'id' => 2,
                'invoice_id' => 'INV-1002',
                'sr_invoice' => 'SR-5002',
                'date' => now()->subDays(2),
                'customer' => (object)['name' => 'Jane Smith'],
                'payable_amount' => 2500,
                'paid_amount' => 2500,
                'due_amount' => 0,
            ],
            (object)[
                'id' => 3,
                'invoice_id' => 'INV-1003',
                'sr_invoice' => null,
                'date' => now()->subDays(3),
                'customer' => null,
                'payable_amount' => 3200,
                'paid_amount' => 1000,
                'due_amount' => 2200,
            ],
            (object)[
                'id' => 4,
                'invoice_id' => 'INV-1004',
                'sr_invoice' => 'SR-5004',
                'date' => now()->subDays(4),
                'customer' => (object)['name' => 'Michael Lee'],
                'payable_amount' => 1800,
                'paid_amount' => 1800,
                'due_amount' => 0,
            ],
        ]);
        return view('admin.sales.sales', compact('sales'));
    }
    public function create( ){

        return view('admin.sales.sales-form');

    }
    public function collection(){

    }
    public function invoiceWiseCollection(){

    }
    public function store(StoreSaleRequest $request, SaleService $service)
    {

        $data=$request->validated();
        // return $data;
        $sale = $service->createSale($data);

        return response()->json([
            'status'=> true,
            'message' => 'Sale completed',
            'sale' => $sale
        ],200);
    }
    public function show(Sale $sale){
        $sale->load(['items.product', 'customer']);
        return view('admin.sales.saledetails', compact('sale'));
    }

    public function edit(){}

    public function delete(){}


    public function getProducts(Request $request)
    {
        $products = Product::where('name', 'like', '%' . $request->q . '%')
            ->select('id', 'name', 'code')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function getBatches($id)
    {
        $batches = ProductBatch::where('product_id', $id)->with('unit:id,name')
            ->select('id', 'batch_no', 'quantity', 'sales_price','unit_id')
            ->get();

        return response()->json($batches);
    }

    public function invoices($id){

    }


    public function data(Request $request)
{
    $query = Sale::query()
        ->select([
            'id',
            'invoice_no',
            'grand_total',
            'paid',
            'due',
            'status',
            'created_at',
            'customer_id'
        ])

        ->with('customer:id,name,phone')
        ->withCount('items')
        ->where('customer_id', '!=', null)
        ->when($request->filled('customer_id'), function ($q) use ($request) {
            $q->where('customer_id', $request->customer_id);
        })

        ->when($request->filled('invoice_no'), function ($q) use ($request) {
            $q->where('invoice_no', $request->invoice_no);
        })

        ->when($request->filled('sale_date'), function ($q) use ($request) {

            $q->whereDate(
                'sale_date',
                \Carbon\Carbon::parse($request->sale_date)->format('Y-m-d')
            );
        });

    return DataTables::of($query)

        ->addColumn('customer', function ($sale) {

            return '
                <div class="p-2">
                    <div class="font-medium">
                        '.$sale->customer?->name.'
                    </div>

                    <div class="text-xs text-gray-500">
                        '.$sale->customer?->phone.'
                    </div>
                </div>
            ';
        })

        ->addColumn('date', function ($sale) {
            return $sale->created_at->format('d M Y H:i');
        })

        ->addColumn('items', function ($sale) {
            return $sale->items_count;
        })

        ->addColumn('due', function ($sale) {

            $due = number_format($sale->due, 2);

            if ($sale->due > 0) {
                return '
                    <span class="px-2 py-1 text-red-600 bg-red-100 rounded">
                        '.$due.'
                    </span>
                ';
                }else if($sale->due < 0){
                return '
                    <span class="px-2 py-1 text-white-100 bg-purple-600 rounded">
                        '.$due.'
                    </span>
                ';

            }

            return '
                <span class="px-2 py-1 text-green-600 bg-green-100 rounded">
                    '.$due.'
                </span>
            ';
        })

        ->addColumn('action', function ($sale) {

            return '
                <div class="flex items-center gap-2">

                    <a href="'.route('admin.sales.show', $sale->id).'"
                    class="p-2 text-blue-600 hover:bg-blue-50 rounded-md">
                        View
                    </a>

                    <a href="'.route('admin.sales.invoice', $sale->id).'"
                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-md">
                        Invoice
                    </a>

                    <button
                        class="p-2 text-red-600 hover:bg-red-50 rounded-md cancelSaleBtn"
                        data-id="'.$sale->id.'">
                        Cancel
                    </button>

                </div>
            ';
        })

        ->rawColumns(['action', 'due', 'customer'])
        ->make(true);
}
}
