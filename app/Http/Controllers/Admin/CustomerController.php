<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->q;

        $customers = Customer::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'phone']);

        return response()->json([
            'data' => $customers
        ]);
    }
    public function invoice(Request $request)
    {
        $query = $request->q;

        $invoices = Sale::query()
            ->when($query, function ($q) use ($query) {
                $q->where('invoice_no', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'invoice_no']);

        return response()->json([
            'data' => $invoices
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:customers,phone|min:11|max:11|starts_with:013,014,015,016,017,018,019',
            'address' => 'nullable|string|max:500',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer created successfully',
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
            ]
        ]);
    }
}
